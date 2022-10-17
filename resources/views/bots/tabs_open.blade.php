<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li @if($htmlTag=='bots')class="active" @endif><a href="{!! url('/bots/'.$bot->slug.'/edit') !!}" data-test="bot-tab-link">Bot</a></li>
        <li @if($htmlTag=='bot-properties')class="active" @endif><a href="{!! url('/bot/properties/'.$bot->slug.'/list') !!}" data-test="bot-properties-tab-link">Properties</a></li>
        <li @if($htmlTag=='bot-category-groups')class="active" @endif><a href="{!! url('/bot/categories/'.$bot->slug.'/list') !!}" data-test="bot-category-groups-tab-link">Knowledge</a></li>
        <li @if($htmlTag=='bot-chat')class="active" @endif><a href="{!! url('/bot/'.$bot->slug.'/chat') !!}" data-test="bot-chat-tab-link">Chat</a></li>
        <li @if($htmlTag=='bot-plugins')class="active" @endif><a href="{!! url('/bot/plugins/'.$bot->slug.'/list') !!}" data-test="bot-plugins-tab-link">Plugins</a></li>
        <li @if($htmlTag=='conversations')class="active" @endif><a href="{!! url('/bot/logs/'.$bot->slug.'/list') !!}" data-test="conversations-tab-link">Logs</a></li>
        <li @if($htmlTag=='bot-widgets')class="active" @endif><a href="{!! url('/bot/widget/'.$bot->slug.'/list') !!}" data-test="widgets-tab-link">Widgets</a></li>
        <li @if($htmlTag=='bot-stats')class="active" @endif><a href="{!! url('/bot/stats/'.$bot->slug.'/list') !!}" data-test="rating-tab-link">Stats</a></li>
        <li @if($htmlTag=='bot-keys')class="active" @endif><a href="{!! url('/bot/keys/'.$bot->slug.'/list') !!}" data-test="widgets-tab-link">Keys</a></li>
        <li @if($htmlTag=='bot-allowed-sites')class="active" @endif><a href="{!! url('/bot/sites/'.$bot->slug.'/list') !!}" data-test="widgets-tab-link">Sites</a></li>
    </ul>

    <div class="tab-content">
