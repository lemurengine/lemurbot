<li class="{{ Request::is('dashboard*') ? 'active' : '' }} side-bar-top-level-menu">
    <a href="{!!  url('/dashboard')  !!}" data-test="sidebar-link-dashboard"><i class="fa fa-home !!}"></i><span>Dashboard</span></a>
</li>


<li class="{{ Request::is('bot*') ? 'active' : '' }}">
    <a href="{{ url('botList') }}" data-test="sidebar-link-bots-main"><i class="fa fa-smile-o"></i><span>Bots</span></a>
</li>




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

@if(LemurPriv::isAdmin(Auth::user()))

    <li class="treeview {{ Request::is('sections*')  || Request::is('botAllowedSites*') || Request::is('botRatings*') ||  Request::is('botKeys*') || Request::is('wildcards*') || Request::is('bots*') ||  Request::is('normalizations*') || Request::is('botWordSpellingGroups*') || Request::is('botProperties*') || Request::is('botCategoryGroups*')  ||  Request::is('users*')||  Request::is('wordSpellingGroups*') || Request::is('wordSpellings*') || Request::is('languages*') || Request::is('wordTransformations*') }} side-bar-top-level-menu" data-test="sidebar-parent-master-data-li">

        <a href="#">
            <i class="fa fa-th-list"></i>
            <span>Master Data</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-master-data-button"></i>
        </span>
        </a>
        <ul class="treeview-menu" style="{{ Request::is('sections*')  || Request::is('botAllowedSites*') || Request::is('botRatings*') ||  Request::is('botKeys*') || Request::is('wildcards*') || Request::is('bots*') ||  Request::is('normalizations*') || Request::is('botWordSpellingGroups*') || Request::is('botProperties*') || Request::is('botCategoryGroups*')  || Request::is('users*') || Request::is('wordSpellingGroups*') || Request::is('wordSpellings*') || Request::is('languages*') || Request::is('wordTransformations*')  ? 'display:block;' : '' }}">

            <li class="{{ Request::is('bots*') ? 'active' : '' }}">
                <a href="{{ url('bots') }}" data-test="sidebar-link-bots-sub"><i class="fa fa-circle-o"></i><span>Bots</span></a>
            </li>

            <li class="{{ Request::is('botAllowedSites*') ? 'active' : '' }}">
                <a href="{{ route('botAllowedSites.index') }}" data-test="sidebar-link-botAllowedSites"><i class="fa fa-circle-o"></i><span>Bot Allowed Sites</span></a>
            </li>

            <li class="{{ Request::is('botProperties*') ? 'active' : '' }}">
                <a href="{{ route('botProperties.index') }}" data-test="sidebar-link-botProperties"><i class="fa fa-circle-o"></i><span>Bot Properties</span></a>
            </li>

            <li class="{{ Request::is('botCategoryGroups*') ? 'active' : '' }}">
                <a href="{{ route('botCategoryGroups.index') }}" data-test="sidebar-link-botCategoryGroups"><i class="fa fa-circle-o"></i><span>Bot Category Groups</span></a>
            </li>

            <li class="{{ Request::is('botWordSpellingGroups*') ? 'active' : '' }}">
                <a href="{{ route('botWordSpellingGroups.index') }}" data-test="sidebar-link-botWordSpellingGroups"><i class="fa fa-circle-o"></i><span>Bot Word Spelling Groups</span></a>
            </li>

            <li class="{{ Request::is('botKeys*') ? 'active' : '' }}">
                <a href="{{ route('botKeys.index') }}" data-test="sidebar-link-botKeys"><i class="fa fa-circle-o"></i><span>Bot Keys</span></a>
            </li>

            <li class="{{ Request::is('botRatings*') ? 'active' : '' }}">
                <a href="{{ route('botRatings.index') }}" data-test="sidebar-link-botRatings"><i class="fa fa-circle-o"></i><span>Bot Ratings</span></a>
            </li>


            <li class="{{ Request::is('languages*') ? 'active' : '' }}">
                <a href="{{ route('languages.index') }}" data-test="sidebar-link-languages"><i class="fa fa-circle-o"></i><span>Languages</span></a>
            </li>

            <li class="{{ Request::is('wordTransformations*') ? 'active' : '' }}">
                <a href="{{ route('wordTransformations.index') }}" data-test="sidebar-link-wordTransformations"><i class="fa fa-circle-o"></i><span>Word Transformations</span></a>
            </li>


            <li class="{{ Request::is('wordSpellingGroups*') ? 'active' : '' }}">
                <a href="{{ route('wordSpellingGroups.index') }}" data-test="sidebar-link-wordSpellingGroups"><i class="fa fa-circle-o"></i><span>Word Spelling Groups</span></a>
            </li>


            <li class="{{ Request::is('wordSpellings*') ? 'active' : '' }}">
                <a href="{{ route('wordSpellings.index') }}" data-test="sidebar-link-wordSpellings"><i class="fa fa-circle-o"></i><span>Word Spellings</span></a>
            </li>

            <li class="{{ Request::is('normalizations*') ? 'active' : '' }}">
                <a href="{{ route('normalizations.index') }}" data-test="sidebar-link-normalizations"><i class="fa fa-circle-o"></i><span>Normalizations</span></a>
            </li>

            <li class="{{ Request::is('wildcards*') ? 'active' : '' }}">
                <a href="{{ route('wildcards.index') }}" data-test="sidebar-link-wildcards"><i class="fa fa-circle-o"></i><span>Wildcards</span></a>
            </li>


            <li class="{{ Request::is('sections*') ? 'active' : '' }}">
                <a href="{{ route('sections.index') }}" data-test="sidebar-link-users"><i class="fa fa-circle-o"></i><span>Sections</span></a>
            </li>

            <li class="{{ Request::is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" data-test="sidebar-link-users"><i class="fa fa-circle-o"></i><span>Bot Users</span></a>
            </li>


        </ul>
    </li>

    <li class="{{ Request::is('test*') ? 'active' : '' }}">
        <a href="{{ url('test') }}" data-test="sidebar-link-test"><i class="fa fa-check-square"></i><span>Test</span></a>
    </li>

@endif



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
