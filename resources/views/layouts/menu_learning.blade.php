<li class="treeview {{ Request::is('machineLearntCategories*') || Request::is('clientCategories*') || Request::is('emptyResponses*') }} side-bar-top-level-menu" data-test="sidebar-parent-learning-li">
<a href="#">
        <i class="fa fa-mortar-board"></i>
        <span>Learning</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-learning"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ Request::is('machineLearntCategories*') || Request::is('clientCategories*') || Request::is('emptyResponses*')  ? 'display:block;' : '' }}">

        <li class="{{ Request::is('machineLearntCategories*') ? 'active' : '' }}">
            <a href="{{ route('machineLearntCategories.index') }}" data-test="sidebar-link-machineLearntCategories"><i class="fa fa-circle-o"></i><span>ML Categories</span></a>
        </li>

        <li class="{{ Request::is('emptyResponses*') ? 'active' : '' }}">
            <a href="{{ route('emptyResponses.index') }}" data-test="sidebar-link-emptyResponses"><i class="fa fa-circle-o"></i><span>Empty Responses</span></a>
        </li>

        <li class="{{ Request::is('clientCategories*') ? 'active' : '' }}">
            <a href="{{ route('clientCategories.index') }}" data-test="sidebar-link-clientCategories"><i class="fa fa-circle-o"></i><span>Client Categories</span></a>
        </li>

    </ul>
</li>
