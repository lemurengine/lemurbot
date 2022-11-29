@if(LemurPriv::isAdmin(Auth::user()))

    <li class="treeview {{ Request::is('botPlugins*') || Request::is('plugins*') || Request::is('sections*')  || Request::is('botAllowedSites*') || Request::is('botRatings*') ||  Request::is('botKeys*') || Request::is('wildcards*') || Request::is('bots*') ||  Request::is('normalizations*') || Request::is('botWordSpellingGroups*') || Request::is('botProperties*') || Request::is('botCategoryGroups*')  ||  Request::is('users*')||  Request::is('wordSpellingGroups*') || Request::is('wordSpellings*') || Request::is('languages*') || Request::is('wordTransformations*') }} side-bar-top-level-menu" data-test="sidebar-parent-master-data-li">

        <a href="#">
            <i class="fa fa-th-list"></i>
            <span>Master Data</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"  data-test="sidebar-parent-master-data-button"></i>
        </span>
        </a>
        <ul class="treeview-menu" style="{{ Request::is('botPlugins*') || Request::is('plugins*') || Request::is('sections*')  || Request::is('botAllowedSites*') || Request::is('botRatings*') ||  Request::is('botKeys*') || Request::is('wildcards*') || Request::is('bots*') ||  Request::is('normalizations*') || Request::is('botWordSpellingGroups*') || Request::is('botProperties*') || Request::is('botCategoryGroups*')  || Request::is('users*') || Request::is('wordSpellingGroups*') || Request::is('wordSpellings*') || Request::is('languages*') || Request::is('wordTransformations*')  ? 'display:block;' : '' }}">

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

            <li class="{{ Request::is('botPlugins*') ? 'active' : '' }}">
                <a href="{{ route('botPlugins.index') }}" data-test="sidebar-link-botPlugins"><i class="fa fa-circle-o"></i><span>Bot Plugins</span></a>
            </li>

            <li class="{{ Request::is('plugins*') ? 'active' : '' }}">
                <a href="{{ route('plugins.index') }}" data-test="sidebar-link-Plugins"><i class="fa fa-circle-o"></i><span>Plugins</span></a>
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
