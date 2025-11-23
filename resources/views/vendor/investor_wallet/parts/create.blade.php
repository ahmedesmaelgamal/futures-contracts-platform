<div class="modal-body">

    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $storeRoute }}">
        @csrf

        <div class="row">




            <div class="col-6">
                <div class="form-group">
                    <label for="type" class="form-control-label">نوع العملية
                    </label>
                    <select class="form-control select2" name="type" id="typeOfOperation">
                        <option value="" selected disabled>اختر نوع العملية</option>
                        <option value="0">إيداع</option>
                        <option value="1">سحب</option>
                    </select>
                </div>
            </div>
             <div class="col-6">
                <div class="form-group">
                    <label for="email" class="form-control-label">المستثمر
                    </label>
                    <select name="investor_id" id="investor_id" class="form-control select2">
                        <option value="" selected disabled>اختر المستثمر</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label for="amount" class="form-control-label">المبلغ
                    </label>
                    <input type="number" step="0.01" min="0.01" class="form-control" name="amount"
                        id="amount">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="note" class="form-control-label">ملاحظات
                    </label>
                    <textarea name="note" id="note" class="form-control" rows="5"></textarea>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">حفظ
            </button>
        </div>

    </form>
</div>
<script>
    $('select#typeOfOperation,#investor_id').select2({
        dropdownParent: $('#editOrCreate .modal-content')

    });
</script>

