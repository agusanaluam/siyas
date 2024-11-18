<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="{{ Route::is('dashboard', '/') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" ><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ Request::is('donation.create') ? 'active' : '' }}"><a href="javascript:void(0);" id="createDonation"><i
                                    data-feather="smartphone"></i><span>Create Donation</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Manage</h6>
                    <ul>
                        @if (Auth::user()->level != 'volunteer')
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ Route::is('campaign.categories','campaign.list','campaign.create','campaign.edit','campaign.list.pending','campaign.list.running','campaign.list.closed') ? 'active subdrop' : '' }}"><i
                                    data-feather="tv"></i><span>Campaign</span><span class="menu-arrow"></span></a>
                            <ul>
                                @if (Auth::user()->level == 'administrator')
                                <li><a href="{{ route('campaign.categories') }}"
                                    class="{{ Route::is('campaign.categories') ? 'active' : '' }}">Categories</a></li>
                                <li><a href="{{ route('campaign.create') }}"
                                    class="{{ Route::is('campaign.create','campaign.edit') ? 'active' : '' }}">Add Campaigns</a></li>
                                @endif
                                <li><a href="{{ route('campaign.list') }}"
                                    class="{{ Route::is('campaign.list') ? 'active' : '' }}">Campaigns List</a></li>
                                <li><a href="{{ route('campaign.list.pending') }}"
                                    class="{{ Route::is('campaign.list.pending') ? 'active' : '' }}">Pending Campaigns</a></li>
                                <li><a href="{{ route('campaign.list.running') }}"
                                    class="{{ Route::is('campaign.list.running') ? 'active' : '' }}">Running Campaigns</a></li>
                                <li><a href="{{ route('campaign.list.closed') }}"
                                    class="{{ Route::is('campaign.list.closed') ? 'active' : '' }}">Closed Campaigns</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ Route::is('donation.list','donation.history','donation.list.transfer','mutation.list') ? 'active subdrop' : '' }}"><i
                                    data-feather="package"></i><span>Donation</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('donation.list') }}"
                                        class="{{ Route::is('donation.list') ? 'active' : '' }}">Donation List</a></li>
                                <li><a href="{{ route('donation.list.transfer') }}"
                                        class="{{ Route::is('donation.list.transfer') ? 'active' : '' }}">Tranfered Donation</a></li>
                                <li><a href="{{ route('donation.history') }}"
                                        class="{{ Route::is('donation.history') ? 'active' : '' }}">Donation History</a></li>
                            @if (Auth::user()->level != 'volunteer')
                                <li><a href="{{ route('mutation.list') }}"
                                        class="{{ Route::is('mutation.list') ? 'active' : '' }}">Mutation Donation</a></li>
                            @endif
                            </ul>
                        </li>
                        @if (Auth::user()->level != 'volunteer')
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ Route::is('group.list','volunteer.list','volunteer.list.inactive') ? 'active subdrop' : '' }}"><i
                                    data-feather="users"></i><span>Volunteers</span><span class="menu-arrow"></span></a>
                            <ul>
                            @if (Auth::user()->level == 'administrator')
                                <li><a href="{{ route('group.list') }}"
                                        class="{{ Route::is('group.list') ? 'active' : '' }}">Group</a></li>
                            @endif
                                <li><a href="{{ route('volunteer.list') }}"
                                        class="{{ Route::is('volunteer.list') ? 'active' : '' }}">Volunteer List</a></li>
                                <li><a href="{{ route('volunteer.list.inactive') }}"
                                        class="{{ Route::is('volunteer.list.inactive') ? 'active' : '' }}">Inactive Volunteer</a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="{{ Route::is('user.profile') ? 'active' : '' }}">
                            <a href="{{ route('user.profile') }}"><i data-feather="user"></i><span>Profile</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="$('#logout-form').submit()" ><i data-feather="log-out"></i><span>Logout</span> </a>
                        </li>
                    </ul>
                </li>
				</li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
