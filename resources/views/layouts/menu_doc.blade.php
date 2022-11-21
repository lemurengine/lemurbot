<li class="treeview {{ Request::is('customDocs*') ? 'active' : '' }} side-bar-top-level-menu" data-test="sidebar-parent-documentation-li">
    <a href="#">
        <i class="fa fa-book"></i>
        <span>Documentation</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-learning"></i>
        </span>
    </a>
    <ul class="treeview-menu" style="{{ Request::is('customDocs*')  ? 'display:block;' : '' }}">

        <li>
            <a href="https://docs.lemurengine.com" data-test="sidebar-link-standard-docs"><i class="fa fa-circle-o"></i><span>Standard Docs</span></a>
        </li>

        <li class="{{ Request::is('customDocs*') ? 'active' : '' }}">
            <a href="{{ route('customDocs.index') }}" data-test="sidebar-link-custom-docs"><i class="fa fa-circle-o"></i><span>Custom Docs</span></a>
        </li>

    </ul>
</li>
