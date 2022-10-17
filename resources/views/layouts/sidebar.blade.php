<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! Avatar::create(Auth::user()->name)->toBase64() !!}"
                     class="user-image" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p data-test="sidebar-user-name">{{ Auth::user()->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ (LemurPriv::isAdmin(Auth::user())?'Admin':'Member')}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <form action="{!! url('/quickchat') !!}" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" value="@if(!empty(Session::get('target_bot'))){!! Session::get('target_bot')->slug !!}@endif" placeholder="Quick Chat...">
                <span class="input-group-btn">
                <button type="submit" id="search-btn" class="btn btn btn-info">
                  <i class="fa fa-forward"></i> Go
                </button>
              </span>
            </div>
        </form>
        <ul class="sidebar-menu" data-widget="tree">
            @include('lemurbot::layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
