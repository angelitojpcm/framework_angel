  <!-- Start Header Top 
    ============================================= -->
  <div class="top-bar-area bg-dark text-light inline inc-border">
      <div class="container">
          <div class="row align-center">

              <div class="col-lg-7 col-md-12 left-info">
                  <div class="item-flex">
                      <ul class="list">
                          <li>
                              <i class="fas fa-phone"></i> Have any question? +123 456 7890
                          </li>
                          <li>
                              <i class="fas fa-bullhorn"></i> <a href="#">Become an Instructor</a>
                          </li>
                      </ul>
                  </div>
              </div>

              <div class="col-lg-5 col-md-12 right-info">
                  <div class="item-flex">
                      <div class="social">
                          <ul>
                              <li>
                                  <a href="#"><i class="fab fa-facebook-f"></i></a>
                              </li>
                              <li>
                                  <a href="#"><i class="fab fa-twitter"></i></a>
                              </li>
                              <li>
                                  <a href="#"><i class="fab fa-pinterest-p"></i></a>
                              </li>
                              <li>
                                  <a href="#"><i class="fab fa-linkedin-in"></i></a>
                              </li>
                          </ul>
                      </div>
                      <div class="button">
                        <?php 
                        $account = '';
                        if ($d->auth->validate()) {
                            $account .= '<a href="' . BASEPATH . 'auth/logout"><i class="fa fa-sign-out-alt"></i> Logout</a>';
                        } else {
                            $account .= '<a href="' . BASEPATH . 'auth/register"> Registrase</a>';
                            $account .= '<a href="' . BASEPATH . 'auth/login"><i class="fa fa-sign-in-alt"></i> Iniciar Sesión</a>';
                        }
                        echo $account;
                        ?>
                      </div>
                  </div>
              </div>

          </div>
      </div>
  </div>
  <!-- End Header Top -->

  <!-- Header 
    ============================================= -->
  <header id="home">

      <!-- Start Navigation -->
      <nav class="navbar shadow-less navbar-default navbar-sticky bootsnav">

          <div class="container">

              <!-- Start Atribute Navigation -->
              <div class="attr-nav">
                  <form action="#">
                      <input type="text" placeholder="Search" class="form-control" name="text">
                      <button type="submit">
                          <i class="fa fa-search"></i>
                      </button>
                  </form>
              </div>
              <!-- End Atribute Navigation -->

              <!-- Start Header Navigation -->
              <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                      <i class="fa fa-bars"></i>
                  </button>
                  <a class="navbar-brand" href="index.html">
                      <img src="assets/img/logo.png" class="logo" alt="Logo">
                  </a>
              </div>
              <!-- End Header Navigation -->

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="navbar-menu">
                  <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle active" data-toggle="dropdown">Home</a>
                          <ul class="dropdown-menu">
                              <li><a href="index.html">Home Version One</a></li>
                              <li><a href="index-2.html">Home Version Two</a></li>
                              <li><a href="index-3.html">Home Version Three</a></li>
                              <li><a href="index-4.html">Home Version Four</a></li>
                          </ul>
                      </li>
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pages</a>
                          <ul class="dropdown-menu">
                              <li><a href="about-us.html">About Us</a></li>
                              <li class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Events</a>
                                  <ul class="dropdown-menu">
                                      <li><a href="events.html">Events Style 1</a></li>
                                      <li><a href="events-2.html">Events Style 2</a></li>
                                  </ul>
                              </li>
                              <li><a href="faq.html">Faq</a></li>
                              <li><a href="gallery.html">Gallery</a></li>
                              <li><a href="contact.html">Contact</a></li>
                              <li><a href="404.html">Error Page</a></li>
                          </ul>
                      </li>
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Courses</a>
                          <ul class="dropdown-menu">
                              <li><a href="courses.html">Course One</a></li>
                              <li><a href="courses-2.html">Course Two</a></li>
                              <li><a href="courses-3.html">Course Three</a></li>
                              <li><a href="courses-4.html">Course Four</a></li>
                              <li><a href="courses-5.html">Course Five</a></li>
                              <li><a href="course-details.html">Course Details</a></li>
                          </ul>
                      </li>
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Teachers</a>
                          <ul class="dropdown-menu">
                              <li><a href="advisors.html">Teachers One</a></li>
                              <li><a href="advisors-2.html">Teachers Two</a></li>
                              <li><a href="advisor-single.html">Teacher Single</a></li>
                          </ul>
                      </li>
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                          <ul class="dropdown-menu">
                              <li><a href="blog-standard.html">Blog Standard</a></li>
                              <li><a href="blog-with-sidebar.html">Blog With Sidebar</a></li>
                              <li><a href="blog-2-colum.html">Blog Grid Two Colum</a></li>
                              <li><a href="blog-3-colum.html">Blog Grid Three Colum</a></li>
                              <li><a href="blog-single.html">Blog Single</a></li>
                              <li><a href="blog-single-with-sidebar.html">Blog Single With Sidebar</a></li>
                          </ul>
                      </li>
                      <li>
                          <a href="contact.html">Contact</a>
                      </li>
                  </ul>
              </div><!-- /.navbar-collapse -->
          </div>

      </nav>
      <!-- End Navigation -->

  </header>
  <!-- End Header -->