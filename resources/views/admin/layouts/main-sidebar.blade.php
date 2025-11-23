<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('adminHome') }}">
            <img src="{{ getFile(isset($setting) ? $setting->logo : null) }}" class="header-brand-img" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li>
            <h3>العناصر</h3>
        </li>
        <li>
            <a class="slide-item {{ Route::currentRouteName() == 'adminHome' ? 'active' : '' }}"
               href="{{ route('adminHome') }}" style="margin-right:8px;">
                <i class="fa fa-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسيه</span>
            </a>
        </li>

        @canany(['create_admin', 'read_admin', 'update_admin', 'delete_admin'])
            <!-- admins -->
            <li class="{{ routeActive('admins.index') }}">
                <a class="slide-item {{ routeActive('admins.index') }}" style="margin-right:8px;"
                   href="{{ route('admins.index') }}">
                    <i class="fa fa-user-shield side-menu__icon"></i> <!-- المشرفين Icon -->
                    <span class="side-menu__label">المشرفين</span>

                </a>
            </li>
        @endcanany

        @canany(['create_vendor', 'read_vendor', 'update_vendor', 'delete_vendor'])
            <!-- vendors -->
            <li class="{{ routeActive('admin.vendors.index') }}">
                <a class="slide-item {{ routeActive('admin.vendors.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.vendors.index') }}">
                    <i class="fa fa-building side-menu__icon"></i> <!-- المكاتب Icon -->
                    <span class="side-menu__label">المكاتب</span>
                </a>
            </li>
        @endcanany

        @canany(['create_plan', 'read_plan', 'update_plan', 'delete_plan'])
            <li class="{{ routeActive('Plans.index') }}">
                <a class="slide-item {{ routeActive('Plans.index') }}" style="margin-right:8px;"
                   href="{{ route('Plans.index') }}">
                    <i class="fa fa-th-large side-menu__icon"></i> <!-- الخطط Icon -->
                    <span class="side-menu__label">الخطط</span>

                </a>
            </li>
        @endcanany

        @canany([ 'create_plan_subscription','read_plan_subscription', 'update_plan_subscription', 'delete_plan_subscription'])
            <li class="{{ routeActive('planSubscription.index') }}">
                <a class="slide-item {{ routeActive('planSubscription.index') }}" style="margin-right:8px;"
                   href="{{ route('planSubscription.index') }}">
                    <i class="fa fa-credit-card side-menu__icon"></i> <!-- الاشتراكات Icon -->
                    <span class="side-menu__label">الاشتراكات</span>

                </a>
            </li>
        @endcanany


        @can(['read_investor'])
            <!-- admins -->
            <li class="{{ routeActive('admin.investors.index') }}">
                <a class="slide-item {{ routeActive('admin.investors.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.investors.index') }}">
                    <i class="fas fa-user-tie side-menu__icon"></i> <!-- Admin Icon -->
                    <span class="side-menu__label">المستثمرين</span>

                </a>
            </li>
        @endcan
        @can(['read_client'])
            <!-- vendors -->
            <li class="{{ routeActive('admin.clients.index') }}">
                <a class="slide-item {{ routeActive('admin.clients.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.clients.index') }}">
                    <i class="fa fa-address-book side-menu__icon"></i> <!-- العملاء Icon -->
                    <span class="side-menu__label">العملاء</span>

                </a>
            </li>
        @endcan
        @canany(['create_unsurpassed', 'update_unsurpassed', 'delete_unsurpassed', 'read_unsurpassed'])
            <li class="{{ routeActive('admin.unsurpasseds.index') }}">
                <a class="slide-item {{ routeActive('admin.unsurpasseds.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.unsurpasseds.index') }}">
                    <i class="fas fa-hand-holding-usd side-menu__icon"></i> <!-- المتعثرين -->
                    <span class="side-menu__label">المتعثرين</span>

                </a>

            </li>
        @endcanany
        @can(['read_order'])
            <!-- vendors -->
            <li class="{{ routeActive('admin.orders.index') }}">
                <a class="slide-item {{ routeActive('admin.orders.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.orders.index') }}">
                    <i class="fa fa-box side-menu__icon"></i> <!-- الطلبات Icon -->
                    <span class="side-menu__label">الطلبات</span>

                </a>
            </li>
        @endcan
        @can(['read_category'])
            <li class="{{ routeActive('admin.categories.index') }}">
                <a class="slide-item {{ routeActive('admin.categories.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.categories.index') }}">
                    <i class="fa fa-list-ul side-menu__icon"></i> <!-- الأصناف Icon -->
                    <span class="side-menu__label">الأصناف</span>

                </a>
            </li>
        @endcan
        @canany(['read_activity_log', 'delete_activity_log'])
            <!-- سجل النظام -->
            <li class="{{ routeActive('admin.activity_logs.index') }}">
                <a class="slide-item {{ routeActive('admin.activity_logs.index') }}" style="margin-right:8px;"
                   href="{{ route('admin.activity_logs.index') }}">
                    <i class="fa fa-history side-menu__icon"></i> <!-- سجل النظام Icon -->
                    <span class="side-menu__label">
                                            سجل النظام
                    </span>
                </a>
            </li>
        @endcanany

        @canany(['read_setting', 'update_setting'])
            <li>
            <a class="slide-item {{ Route::currentRouteName() == 'admin.setting' ? 'active' : '' }}"
               href="{{ route('admin.setting') }}" style="margin-right:8px;">
                <i class="fa fa-cog side-menu__icon"></i> <!-- الإعدادات Icon --> <span class="side-menu__label">
                    الاعدادات</span>
            </a>
        </li>
        @endcanany




        <li>
            <a class="slide-item {{ Route::currentRouteName() == 'admin.logout' ? 'active' : '' }}"
               href="{{ route('admin.logout') }}" style="margin-right:8px;">
                <i class="fa fa-sign-out-alt side-menu__icon"></i> <!-- تسجيل خروج Icon --> <span
                    class="side-menu__label">تسجيل خروج</span>
            </a>
        </li>
    </ul>
</aside>
