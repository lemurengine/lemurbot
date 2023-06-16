    <li class="treeview {{ Request::is('conversationSources*')|| Request::is('conversations*') || Request::is('conversationProperties*') || Request::is('turns*') || Request::is('clients*')}} side-bar-top-level-menu" data-test="sidebar-parent-logs-li">

        <a href="#">
            <i class="fa fa-coffee"></i>
            <span>Logs</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-logs-button"></i>
        </span>
        </a>
        <ul class="treeview-menu" style="{{Request::is('conversationSources*') || Request::is('conversations*') || Request::is('conversationProperties*') || Request::is('turns*') || Request::is('clients*') ? 'display:block;' : '' }}">

            <li class="{{ Request::is('conversations*') ? 'active' : '' }}">
                <a href="{{ route('conversations.index') }}" data-test="sidebar-link-conversations"><i class="fa fa-circle-o"></i><span>Conversations</span></a>
            </li>

            <li class="{{ Request::is('turns*') ? 'active' : '' }}">
                <a href="{{ route('turns.index') }}" data-test="sidebar-link-turns"><i class="fa fa-circle-o"></i><span>Turns</span></a>
            </li>

            <li class="{{ Request::is('conversationProperties*') ? 'active' : '' }}">
                <a href="{{ route('conversationProperties.index') }}" data-test="sidebar-link-conversation-properties"><i class="fa fa-circle-o"></i><span>Conversation Properties</span></a>
            </li>

            <li class="{{ Request::is('conversationSources*') ? 'active' : '' }}">
                <a href="{{ route('conversationSources.index') }}" data-test="sidebar-link-conversation-sources"><i class="fa fa-circle-o"></i><span>Conversation Sources</span></a>
            </li>

            <li class="{{ Request::is('clients*') ? 'active' : '' }}">
                <a href="{{ route('clients.index') }}" data-test="sidebar-link-clients"><i class="fa fa-circle-o"></i><span>Clients</span></a>
            </li>

        </ul>
    </li>
