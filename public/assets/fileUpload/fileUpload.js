(function ($) {
    var fileUploadCount = 0;

    $.fn.fileUpload = function (existingFiles = []) {
        return this.each(function () {
            var fileUploadDiv = $(this);
            var fileUploadId = `fileUpload-${++fileUploadCount}`;

            // Creates HTML content for the file upload area.
            var fileDivContent = `
                <label for="${fileUploadId}" class="file-upload">
                    <div>
                        <i class="material-icons-outlined"></i>
                        <p>Drag & Drop Files Here</p>
                        <span>OR</span>
                        <div>Browse Files</div>
                    </div>
                    <input type="file" id="${fileUploadId}" name="fileUpload[]" multiple hidden />
                </label>
                <input type="hidden" id="deletedFiles-${fileUploadId}" name="deletedFiles[]" value="">
            `;

            fileUploadDiv.html(fileDivContent).addClass("file-container");

            var table = null;
            var tableBody = null;
            var deletedFiles = []; // Array to keep track of deleted files

            // Creates a table containing file information.
            function createTable() {
                table = $(`
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 30%;">File Name</th>
                                <th>Preview</th>
                                <th style="width: 20%;">Size</th>
                                <th>Type</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                `);

                tableBody = table.find("tbody");
                fileUploadDiv.append(table);
            }

            // Adds the information of uploaded files to table.
            function handleFiles(files) {
                if (!table) {
                    createTable();
                }

                if (files.length > 0) {
                    $.each(files, function (index, file) {
                        var fileName = file.name || file.fileName; // Adjust for existing files if needed
                        var fileSize = file.size ? (file.size / 1024).toFixed(2) + " KB" : 'N/A'; // Adjust size for existing files if needed
                        var fileType = file.type || 'unknown'; // Adjust for existing files if needed
                        var preview = fileType.startsWith("image")
                            ? `<img src="${file.url || URL.createObjectURL(file)}" alt="${fileName}" height="30">` // Use `file.url` for existing files
                            : `<i class="material-icons-outlined">visibility_off</i>`;

                        tableBody.append(`
                            <tr data-filename="${fileName}" data-url="${file.url || ''}">
                                <td>${index + 1}</td>
                                <td>${fileName}</td>
                                <td>${preview}</td>
                                <td>${fileSize}</td>
                                <td>${fileType}</td>
                                    <td><button type="button" class="deleteBtn"><i class="material-icons-outlined"> X</i></button></td>
                            </tr>
                        `);
                    });

                    tableBody.find(".deleteBtn").click(function () {
                        var row = $(this).closest("tr");
                        var fileName = row.data("filename");
                        var fileUrl = row.data("url");

                        // Add the file URL to the deletedFiles array
                        if (fileUrl) {
                            deletedFiles.push(fileUrl);

                            // Update the hidden input with the deleted files
                            fileUploadDiv.find(`#deletedFiles-${fileUploadId}`).val(JSON.stringify(deletedFiles));
                        }

                        // Remove the row
                        row.remove();

                        if (tableBody.find("tr").length === 0) {
                            tableBody.append('<tr><td colspan="6" class="no-file">No files selected!</td></tr>');
                        }
                    });
                }
            }

            function handleOldFiles(files) {
                if (!table) {
                    createTable();
                }

                if (files.length > 0) {
                    $.each(files, function (index, file) {
                        var fileName = file.name || file.fileName; // Adjust for existing files if needed
                        var fileSize = file.size ? (file.size / 1024).toFixed(2) + " KB" : 'N/A'; // Adjust size for existing files if needed
                        var fileType = file.type || 'unknown'; // Adjust for existing files if needed
                        var preview = `<img src="${file.url}" alt="${fileName}" height="30">`;

                        tableBody.append(`
                            <tr data-filename="${fileName}" data-url="${file.url}">
                                <td>${index + 1}</td>
                                <td>${fileName}</td>
                                <td>${preview}</td>
                                <td>${fileSize}</td>
                                <td>${fileType}</td>
                                <td><button type="button" class="deleteBtn"><i class="material-icons-outlined">X</i></button></td>
                            </tr>
                        `);
                    });

                    tableBody.find(".deleteBtn").click(function () {
                        var row = $(this).closest("tr");
                        var fileName = row.data("filename");
                        var fileUrl = row.data("url");

                        // Add the file URL to the deletedFiles array
                        if (fileUrl) {
                            deletedFiles.push(fileUrl);

                            // Update the hidden input with the deleted files
                            fileUploadDiv.find(`#deletedFiles-${fileUploadId}`).val(JSON.stringify(deletedFiles));
                        }

                        // Remove the row
                        row.remove();

                        if (tableBody.find("tr").length === 0) {
                            tableBody.append('<tr><td colspan="6" class="no-file">No files selected!</td></tr>');
                        }
                    });
                }
            }

            // Load existing files if provided
            if (existingFiles.length > 0) {
                if (!table) {
                    createTable();
                }
                handleOldFiles(existingFiles);
            }

            // Events triggered after dragging files.
            fileUploadDiv.on({
                dragover: function (e) {
                    e.preventDefault();
                    fileUploadDiv.toggleClass("dragover", e.type === "dragover");
                },
                drop: function (e) {
                    e.preventDefault();
                    fileUploadDiv.removeClass("dragover");
                    handleFiles(e.originalEvent.dataTransfer.files);
                },
            });

            // Event triggered when file is selected.
            fileUploadDiv.find(`#${fileUploadId}`).change(function () {
                handleFiles(this.files);
            });
        });
    };
})(jQuery);
