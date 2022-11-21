 <li class="treeview {{ Request::is('maps*') || Request::is('mapValues*')|| Request::is('sets*')|| Request::is('setValues*')|| Request::is('categoriesUpload*') || Request::is('categories*') || Request::is('categoryGroups*') }} side-bar-top-level-menu" data-test="sidebar-parent-knowledge-li">
        <a href="#">
            <i class="fa fa-tree"></i>
            <span>Knowledge</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-knowledge-button"></i>
        </span>
        </a>
        <ul class="treeview-menu" style="{{  Request::is('maps*') || Request::is('mapValues*')|| Request::is('sets*')|| Request::is('setValues*')|| Request::is('categoriesUpload*') || Request::is('categories*') || Request::is('categoryGroups*')|| Request::is('wordCensors*') || Request::is('wordCensorGroups*')  ? 'display:block;' : '' }}">

            <li class="{{ Request::is('categoryGroups*') ? 'active' : '' }}">
                <a href="{{ route('categoryGroups.index') }}" data-test="sidebar-link-categoryGroups"><i class="fa fa-circle-o"></i><span>Category Groups</span></a>
            </li>

            <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}" data-test="sidebar-link-categories"><i class="fa fa-circle-o"></i><span>Categories</span></a>
            </li>

            <li class="{{ Request::is('maps*') ? 'active' : '' }}">
                <a href="{{ route('maps.index') }}" data-test="sidebar-link-maps"><i class="fa fa-circle-o"></i><span>Maps</span></a>
            </li>

            <li class="{{ Request::is('mapValues*') ? 'active' : '' }}">
                <a href="{{ route('mapValues.index') }}" data-test="sidebar-link-mapValues"><i class="fa fa-circle-o"></i><span>Map Values</span></a>
            </li>

            <li class="{{ Request::is('sets*') ? 'active' : '' }}">
                <a href="{{ route('sets.index') }}" data-test="sidebar-link-sets"><i class="fa fa-circle-o"></i><span>Sets</span></a>
            </li>

            <li class="{{ Request::is('setValues*') ? 'active' : '' }}">
                <a href="{{ route('setValues.index') }}" data-test="sidebar-link-setValues"><i class="fa fa-circle-o"></i><span>Set Values</span></a>
            </li>



        </ul>
    </li>
