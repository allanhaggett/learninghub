<nav class="navbar bg-dark-subtle sticky-top navbar-expand-lg px-3 mb-5" data-bs-theme="dark" role="navigation" aria-label="Primary menu">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon text-white"></span>
    </button>

    <a href="index.php" class="wordmark navbar-brand order-lg-first me-lg-3">

        Learning<span style="color: #ebba44; font-weight: bold;">HUB</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false" aria-label="Toggle search">
        <span class="search-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 mb-lg-0 order-1 order-lg-2">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="about/" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                About</a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="about/">About the LearningHUB</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="corporate-learning-partners/">Learning Partners</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="what-is-corp-learning-framework/">Corporate Learning Framework</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="learning-systems/">
                                                Learning Platforms</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="course/">Foundational Learning</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="courses.php">All Courses</a>
            </li>
            
        </ul>
    </div>
    <form method="get" action="/filter.php" class="collapse navbar-collapse mt-1 mt-lg-0" role="search" id="navbarSearch">
        <label for="s" class="visually-hidden">Search</label>
        <input type="search" 
                id="s" 
                class="s p-1 bg-light-subtle flex-grow-1 flex-shrink-1 me-1 rounded-2 border-0" 
                name="s" 
                placeholder="Keyword search" 
                required 
                value="<?php if(!empty($_GET['s'])) echo htmlspecialchars($_GET['s']) ?>">
        <button class="btn btn-sm btn-success" type="submit" class="searchsubmit" aria-label="Submit Search">
            Search
        </button>
    </form>
    <ul class="navbar-nav mb-2 mb-lg-0 order-1 order-lg-2">
    <li class="nav-item dropdown">
            <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (dark)">
              <svg width="16" height="16" class="bi my-1 theme-icon-active"><use href="#moon-stars-fill"></use></svg>
              <span class="ms-2" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                  <svg width="16" height="16" class="bi me-2 opacity-50"><use href="#sun-fill"></use></svg>
                  Light
                  <svg width="16" height="16" class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="dark" aria-pressed="true">
                  <svg width="16" height="16" class="bi me-2 opacity-50"><use href="#moon-stars-fill"></use></svg>
                  Dark
                  <svg width="16" height="16" class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                  <svg width="16" height="16" class="bi me-2 opacity-50"><use href="#circle-half"></use></svg>
                  Auto
                  <svg width="16" height="16" class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
            </ul>
        </li>
    </ul>
</div>
</nav>