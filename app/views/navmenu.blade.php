@section('navmenu')
<div class="header-wrap">    
    <div id="header">
        <div id="logo">
            <a href="/" style="font-weight: bold;">Whatscomingtokl</a>
        </div>

        <div id="top_quick_links">
            <div class="nowrap">
                <a id="update_profile" href="/email">
                    <span>admin@gmail.com</span>
                </a>
                <span class="top-signout" title="Sign out">
                    <a href="/logout" class="text-link">&nbsp;</a>
                </span>
            </div>
        </div>
        <div id="top_menu">
            <ul id="alt_menu"></ul>
        </div>
        <ul id="menu">
            <li class="dashboard dashboard-active">
                <a href="/" title="Home">&nbsp;</a>
            </li>

            

            <li>
                <a class="drop">Administrator</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/user">
                                    <span>Administrators</span>
                                    <span class="hint">Manage administrators that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            
            <li>
                <a class="drop">Agent</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/agent">
                                    <span>Agents</span>
                                    <span class="hint">Manage agents that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
	        <li>
                <a class="drop">Member</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/member">
                                    <span>Members</span>
                                    <span class="hint">Manage members that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">News</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/news">
                                    <span>News</span>
                                    <span class="hint">Manage news that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">Project</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/project">
                                    <span>Project</span>
                                    <span class="hint">Manage project that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

			<li>
                <a class="drop">Unit</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/unit">
                                    <span>Unit</span>
                                    <span class="hint">Manage unit that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">Event</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank users">
                            	<a href="/event">
                                    <span>Event</span>
                                    <span class="hint">Manage events that are registered at your store.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">Advertisement</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank libraries">
                            	<a href="/advertise">
                                    <span>Advertisement Images</span>
                                    <span class="hint">There are the advertisement images.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">Categories</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank sales">
                            	<a href="/category">
                                    <span>Categories</span>
                                    <span class="hint">Create new categories and edit the existing ones.</span>
                                </a>
                            </li>
							
							<li class="blank categories">
                            	<a href="/subcategory">
                                    <span>Sub Categories</span>
                                    <span class="hint">Create new sub categories and edit the existing ones.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			
			<li>
                <a class="drop">IJM Land</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank libraries">
                            	<a href="/property">
                                    <span>IJM Land</span>
                                    <span class="hint">Create new ijm land and edit the existing ones.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
			<li>
                <a class="drop">Push Notification</a>
                <div class="dropdown-column">
                    <div class="col">
                        <ul>
                            <li class="blank localizations">
                            	<a href="/push">
                                    <span>Push Notification</span>
                                    <span class="hint">Create new push events and edit the existing ones.</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
	            <a href="/index.php">Report</a>
            </li>
        </ul>
			
    </div>
</div>

@show


