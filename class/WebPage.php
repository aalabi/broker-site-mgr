<?php

/**
 * WebPage
 *
 * A class for handling html web page creation
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => October 2022
 * @link        alabiansolutions.com
*/

class WebPageExpection extends Exception
{
}

class WebPage extends AbstractWebPage
{
    /**
     * for creating the head tag on the web page
     *
     * @param array $cssFiles an array of CSS files
     * @param array $jsFiles an array of JS files
     * @param array $metaTags an array of meta tags to be inserted into the head tag
     * @return string
     */
    public function head(array $cssFiles=[], array $jsFiles=[], array $metaTags=[]):string
    {
        $styles = "";
        $scripts = "";
        $metas = "";
        $settings = $this->settings->getDetails();
        $time = $settings->mode == "development" ? "?ver=" . time() : "";
        $backend = $settings->machine->backend;

        if ($cssFiles) {
            foreach ($cssFiles as $aCssFile) {
                $styles .= "<link rel='stylesheet' href='{$aCssFile}{$time}'>";
            }
        }
        if ($jsFiles) {
            foreach ($jsFiles as $aJsFile) {
                $scripts .= "<script src='{$aJsFile}{$time}'></script>";
            }
        }
        if ($metaTags) {
            foreach ($metaTags as $aMetaTag) {
                $metas .= $aMetaTag;
            }
        }

        $head = "
			<!DOCTYPE html>
			<html lang='en'>
			<head>
				<!-- Required meta tags -->
				<meta charset='utf-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='author' content='Alabi A.'>
				$metas
				<title>$this->title</title>
				<link rel='icon' href='" .Functions::getImageUrl(true).Functions::FAVICON. "' type='image/x-icon' />
                <link rel='stylesheet' href='".Functions::getCssUrl(true)."vendors_css.css{$time}'>
                <link rel='stylesheet' href='".Functions::getCssUrl(true)."style.css{$time}'>
                <link rel='stylesheet' href='".Functions::getCssUrl(true)."skin_color.css{$time}'>
				$styles
				$scripts
				<!--
				Developed by Alabian Solutions Limited
				Phone: 08034265103
				Email: info@alabiansolutions.com
				Lead Developer: Alabi A. (facebook.com/alabi.adebayo)
				-->
			</head>
		";
        return $head;
    }

    /**
     * get an array of menu in the app
     *
     * @return array an array of the menu items [topMenu=>[icon, subMenu=>link,subMenu=>link],topMenu=>[icon, link],...]
     */
    protected function menuList():array
    {
        $url = $this->settings->getDetails()->machine->url . $this->settings->getDetails()->machine->backend."/";
        $menu = [
            'Newsletter' => ['fa fa-cog', $url."newsletter"],
            'Downloads'=>['fa fa-cog', $url."downloads"],
            'Research'=>[
                'fa fa-cog',
                'Daily Market Review'=>$url."daily-market-review",
                'Weekly Market Review'=>$url."weekly-market-review",
                'Monthly Market Review'=>$url."monthly-market-review",
                'NASD Market Review'=>$url."nasd-market-review",
            ],
            'Research(Company)'=>[
                'fa fa-cog',
                'Corporate Analysis'=>$url."corporate-analysis",
                'Financial Report'=>$url."financial-report",
            ],
            'News' => ['fa fa-cog', $url."news"],
            'NSE Daily Price'=>['fa fa-cog', $url."nse-daily-price"],
            'Logout'=>['fa fa-sign-out', $url."logout"],
        ];

        $userType = "";
        if (isset($this->loggerId)) {
            $loginUserInfo = (new User(User::profileIdFrmLoginId($this->loggerId)))->getInfo();
            $userType = isset($loginUserInfo['worker'][TblStaff::TYPE]) ? $loginUserInfo['worker'][TblStaff::TYPE] : $loginUserInfo['staff'][TblStaff::TYPE];
        } else {
            unset($menu['Dashboard'], $menu['Staff'], $menu['Salary'], $menu['Worker'], $menu['Payment'], $menu['Settings'], $menu['Profile']);
        }

        $ProfileTypeMgr = new ProfileTypeMgr();
        $allTypes = [];
        foreach ($ProfileTypeMgr->getAllProfileTypes() as $mainType => $subTypes) {
            foreach ($subTypes as $aSubType) {
                $allTypes[] = $aSubType;
            }
        }
        
        if ($userType == $allTypes[0]) {
            unset($menu['Payslip']);
        }
        
        return $menu;
    }

    /**
     * for creating menu for the app
     *
     * @return string an html tag of the menu to be added into the web page
     */
    public function createMenu():string
    {
        $tag = "";

        return $tag;
    }

    /**
     * for creating tags for the masthead of the app
     *
     * @return string an html tag of the masthead to be added into the web page
     */
    public function mastHead():string
    {
        $tag = "";
        
        if (isset($this->loggerId)) {
            $loginUserInfo = (new User(User::profileIdFrmLoginId($this->loggerId)))->getInfo();
            $userType = isset($loginUserInfo['worker'][TblStaff::TYPE]) ? $loginUserInfo['worker'][TblStaff::TYPE] : $loginUserInfo['staff'][TblStaff::TYPE];

            $ProfileTypeMgr = new ProfileTypeMgr();
            $allTypes = [];
            foreach ($ProfileTypeMgr->getAllProfileTypes() as $mainType => $subTypes) {
                foreach ($subTypes as $aSubType) {
                    $allTypes[] = $aSubType;
                }
            }
        }

        $tag = "
            <header class='main-header'>
                <div class='d-flex align-items-center logo-box justify-content-center'>
                    <!-- Logo -->
                    <a href='".$this->settings->getDetails()->machine->url."newsletter' class='logo'>
                        <!-- logo-->
                        <div class='logo-mini'>
                            <span class='light-logo'><img src='".Functions::getImageUrl(true).Functions::LOGO."' width='50' alt='logo'></span>
                            <span class='dark-logo'><img src='".Functions::getImageUrl(true).Functions::LOGO."' width='50' alt='logo'></span>
                        </div>
                        <!-- logo-->
                        <div class='logo-lg'>icon-Align-left
                            <span class='light-logo'><img src='".Functions::getImageUrl(true).Functions::LOGO."' width='100' alt='logo'></span>
                            <span class='dark-logo'><img src='".Functions::getImageUrl(true).Functions::LOGO."' width='100' alt='logo'></span>
                        </div>
                    </a>
                </div>
                <!-- Header Navbar -->
                <nav class='navbar navbar-static-top pl-10'>
                    <!-- Sidebar toggle button-->
                    <div class='app-menu'>
                        <ul class='header-megamenu nav'>
                            <li class='btn-group nav-item'>
                                <a href='#' class='waves-effect waves-light nav-link rounded push-btn' data-toggle='push-menu' role='button'>
                                    <span class='icon-Align-left'><span class='path1'></span><span class='path2'></span><span class='path3'></span></span>
                                </a>
                            </li>                         
                            <li class='btn-group nav-item d-none d-xl-inline-block' >
                                <a href='#' class='waves-effect waves-light nav-link rounded svg-bt-icon' title='' style='width:auto'>                                    
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class='navbar-custom-menu r-side'>
                        <ul class='nav navbar-nav'>
                            <li class='btn-group nav-item d-lg-inline-flex d-none'>                                                          
                            </li>
                            <li class='btn-group nav-item d-lg-inline-flex d-none'>
                                <a href='#' data-provide='fullscreen'
                                    class='waves-effect waves-light nav-link rounded full-screen' title='Full Screen'>
                                    <i class='icon-Expand-arrows'><span class='path1'></span><span class='path2'></span></i>
                                </a>
                            </li>						
                            <!-- Notifications
                            <li class='dropdown notifications-menu'>
                                <a href='#' class='waves-effect waves-light dropdown-toggle' data-toggle='dropdown'
                                    title='Notifications'>
                                    <i class='icon-Notifications'><span class='path1'></span><span class='path2'></span></i>
                                </a>
                                <ul class='dropdown-menu animated bounceIn'>

                                    <li class='header'>
                                        <div class='p-20'>
                                            <div class='flexbox'>
                                                <div>
                                                    <h4 class='mb-0 mt-0'>Notifications</h4>
                                                </div>
                                                <div>
                                                    <a href='#' class='text-danger'>Clear All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>                                        
                                        <ul class='menu sm-scrol'>
                                            <li>
                                                <a href='#'>
                                                    <i class='fa fa-users text-info'></i> Curabitur id eros quis nunc
                                                    suscipit blandit.
                                                </a>
                                            </li>
                                            <li>
                                                <a href='#'>
                                                    <i class='fa fa-warning text-warning'></i> Duis malesuada justo eu
                                                    sapien elementum, in semper diam posuere.
                                                </a>
                                            </li>
                                            <li>
                                                <a href='#'>
                                                    <i class='fa fa-users text-danger'></i> Donec at nisi sit amet tortor
                                                    commodo porttitor pretium a erat.
                                                </a>
                                            </li>
                                            <li>
                                                <a href='#'>
                                                    <i class='fa fa-shopping-cart text-success'></i> In gravida mauris et
                                                    nisi
                                                </a>
                                            </li>                                            
                                        </ul>
                                    </li>
                                    <li class='footer'>
                                        <a href='#'>View all</a>
                                    </li>
                                </ul>
                            </li>
                            -->

                            <!-- User Account-->
                            <li class='dropdown user user-menu'>
                                <a href='#' class='waves-effect waves-light dropdown-toggle' data-toggle='dropdown'
                                    title='User'>
                                    <i class='icon-User'><span class='path1'></span><span class='path2'></span></i>
                                </a>
                                <ul class='dropdown-menu animated flipInX'>
                                    <li class='user-body'>
                                        <a class='dropdown-item' href='".$this->settings->getDetails()->machine->url."profile'>
                                            <i class='ti-user text-muted mr-2'></i>
                                            Profile
                                        </a>
                                        <div class='dropdown-divider'></div>
                                        <a class='dropdown-item' href='".$this->settings->getDetails()->machine->url."logout'>
                                            <i class='ti-lock text-muted mr-2'></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
        ";

        return $tag;
    }

    /**
     * for creating sidebar for the app
     *
     * @return string an html tag of the sidebar to be added into the web page
    */
    public function createSideBar():string
    {
        $tag = "<aside class='main-sidebar'>
			<!-- sidebar-->
			<section class='sidebar'>

				<!-- sidebar menu-->
				<ul class='sidebar-menu' data-widget='tree'>
					<li class='header'>Menu </li>";
        foreach ($this->menuList() as $itemName=>$itemContents) {
            if (count($itemContents) != 2) {
                $tag .= "
                    
                    <li class='treeview'>
                        <a href='#'>
                            <i class='{$itemContents[0]}'><span class='path1'></span><span class='path2'></span></i>
                            <span>$itemName</span>
                            <span class='pull-right-container'>
                                <i class='fa fa-angle-right pull-right'></i>
                            </span>
                        </a>
                        <ul class='treeview-menu'>
                ";
                unset($itemContents[0]);
                foreach ($itemContents as $anItemContentKey => $anItemContentValue) {
                    $tag .= "
                        <li>
                            <a href='$anItemContentValue'>
                                <i class='icon-Commit'>
                                    <span class='path1'></span>
                                    <span class='path2'></span>
                                </i>
                                $anItemContentKey
                            </a>
                        </li>
                    ";
                }
                $tag .= "</ul></li>";
            } else {
                $tag .= "
                    <li class='treeview'>
						<li>
							<a href='{$itemContents[1]}'>
								<i class='{$itemContents[0]}'>
									<span class='path1'></span>
									<span class='path2'></span>
								</i>
								<span>$itemName</span>
							</a>
						</li>
					</li>
                ";
            }
        }
        $tag .= "
            </ul>
			</section>
			<div class='sidebar-footer'>
				<!-- item-->
				<a href='".$this->settings->getDetails()->machine->url."profile' class='link' data-toggle='tooltip' title='' data-original-title='Settings'
					aria-describedby='tooltip92529'><span class='icon-Settings-2'></span>
                </a>
				<!-- item
				<a href='mailbox.html' class='link' data-toggle='tooltip' title='' data-original-title='Email'><span
						class='icon-Mail'></span>
                </a>
				item-->
				<a href='".$this->settings->getDetails()->machine->url."logout' class='link' data-toggle='tooltip' title='' data-original-title='Logout'>
                    <span class='icon-Lock-overturning'>
                        <span class='path1'></span>
                        <span class='path2'></span>
                    </span>
                </a>
			</div>
		</aside>
        ";
        return $tag;
    }

    /**
     * for creating tags for the footer credit section for the app
     *
     * @return string the tag for the footer credit section
     */
    public function footerCredit():string
    {
        $tag = "
            <footer class='main-footer'>
                <div class='pull-right d-none d-sm-inline-block'>
                    <ul class='nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end'>
                        <li class='nav-item'>
                            <a class='nav-link' href='javascript:void(0)'></a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' href='#'></a>
                        </li>
                    </ul>
                </div>
                &copy; ".date("Y")." <a href=''>".$this->settings->getDetails()->sitename." Team</a>. All Rights Reserved.
            </footer>
        ";
        return $tag;
    }

    /**
     * for creating tags for the chat section for the app
     *
     * @return string the tag for the chat section
     */
    public function chatTag():string
    {
        $tag = "
            <div id='chat-box-body'>
                <div id='chat-circle' class='waves-effect waves-circle btn btn-circle btn-lg btn-warning l-h-70'>
                    <div id='chat-overlay'></div>
                    <span class='icon-Group-chat font-size-30'><span class='path1'></span><span class='path2'></span></span>
                </div>

                <div class='chat-box'>
                    <div class='chat-box-header p-15 d-flex justify-content-between align-items-center'>
                        <div class='btn-group'>
                            <button
                                class='waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-45'
                                type='button' data-toggle='dropdown'>
                                <span class='icon-Add-user font-size-22'><span class='path1'></span><span
                                        class='path2'></span></span>
                            </button>
                            <div class='dropdown-menu min-w-200'>						
                                <a class='dropdown-item font-size-16' href='#'>
                                    <span class='icon-User mr-15'><span class='path1'></span><span
                                            class='path2'></span><span class='path3'></span><span class='path4'></span></span>
                                    User</a>
                                <a class='dropdown-item font-size-16' href='#'>
                                    <span class='icon-Group mr-15'><span class='path1'></span><span class='path2'></span></span>
                                    All Users</a>						
                            </div>
                        </div>
                        <div class='text-center flex-grow-1'>
                            <div class='text-dark font-size-18'>Mayra Sibley</div>
                            <div>
                                <span class='badge badge-sm badge-dot badge-primary'></span>
                                <span class='text-muted font-size-12'>Active</span>
                            </div>
                        </div>
                        <div class='chat-box-toggle'>
                            <button id='chat-box-toggle'
                                class='waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45'
                                type='button'>
                                <span class='icon-Close font-size-22'><span class='path1'></span><span
                                        class='path2'></span></span>
                            </button>
                        </div>
                    </div>
                    <div class='chat-box-body'>
                        <div class='chat-box-overlay'>
                        </div>
                        <div class='chat-logs'>
                            <div class='chat-msg user'>
                                <div class='d-flex align-items-center'>
                                    <span class='msg-avatar'>
                                        <img src='../images/avatar/2.jpg' class='avatar avatar-lg'>
                                    </span>
                                    <div class='mx-10'>
                                        <a href='#' class='text-dark hover-primary font-weight-bold'>Mayra Sibley</a>
                                        <p class='text-muted font-size-12 mb-0'>2 Hours</p>
                                    </div>
                                </div>
                                <div class='cm-msg-text'>
                                    Hi there, I'm Jesse and you?
                                </div>
                            </div>
                            <div class='chat-msg self'>
                                <div class='d-flex align-items-center justify-content-end'>
                                    <div class='mx-10'>
                                        <a href='#' class='text-dark hover-primary font-weight-bold'>You</a>
                                        <p class='text-muted font-size-12 mb-0'>3 minutes</p>
                                    </div>
                                    <span class='msg-avatar'>
                                        <img src='../images/avatar/3.jpg' class='avatar avatar-lg'>
                                    </span>
                                </div>
                                <div class='cm-msg-text'>
                                    My name is Anne Clarc.
                                </div>
                            </div>
                            <div class='chat-msg user'>
                                <div class='d-flex align-items-center'>
                                    <span class='msg-avatar'>
                                        <img src='../images/avatar/2.jpg' class='avatar avatar-lg'>
                                    </span>
                                    <div class='mx-10'>
                                        <a href='#' class='text-dark hover-primary font-weight-bold'>Mayra Sibley</a>
                                        <p class='text-muted font-size-12 mb-0'>40 seconds</p>
                                    </div>
                                </div>
                                <div class='cm-msg-text'>
                                    Nice to meet you Anne.<br>How can i help you?
                                </div>
                            </div>
                        </div>
                        <!--chat-log -->
                    </div>
                    <div class='chat-input'>
                        <form>
                            <input type='text' id='chat-input' placeholder='Send a message...' />
                            <button type='submit' class='chat-submit' id='chat-submit'>
                                <span class='icon-Send font-size-22'></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        ";
        $tag = "";
        return $tag;
    }

    /**
     * for creating js files to be added into the web page just before close of body tag
     *
     * @param array $jsFiles an array of JS files
     * @param bool $isOutsidePage true if app homepage
     * @return string an html tag of js files
     */
    public function footerFiles(array $jsFiles=[], bool $isOutsidePage = false):string
    {
        $time = $this->settings->getAllDetails()->mode == "development" ? "?ver=" . time() : "";
        $vendorFiles = $isOutsidePage ? "" : "<script src='".Functions::getJsUrl(true)."template.js$time'></script>";
        $scripts = "
            <script src='".Functions::getJsUrl(true)."vendors.min.js$time'></script>
            <script src='".Functions::getAssetUrl(true)."icons/feather-icons/feather.min.js$time'></script>            
            $vendorFiles   
        ";
        if ($jsFiles) {
            foreach ($jsFiles as $aJsFile) {
                $scripts .= "<script src='{$aJsFile}{$time}'></script>";
            }
        }
        return $scripts;
    }

    /**
     * for creating footer for the app
     *
     * @return string an html tag of the footer to be added into the web page
     */
    public function footer():string
    {
        $tag = "";

        return $tag;
    }

    /**
     * create of response message tag
     * @param string $title the title of the response message
     * @param string $message exact response message
     * @param string $status either POSITIVE|postive or NEGATIVE|negative
     * @return string
     */
    public static function responseTag(string $title, string $message, string $status = parent::RESPONSE_POSITIVE): string
    {
        $tag = "
            <div class=''>
                <div class=''>
                    <h3>$title</h3>                    
                    <div class='clearfix'></div>
                </div>
                <div class='bs-example-popovers'>
                    <div class='alert alert-$status alert-dismissible ' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span>
                        </button>
                        $message
                    </div>                    
                </div>
            </div>
        ";
        return $tag;
    }
}
