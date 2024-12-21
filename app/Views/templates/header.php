<header>
    <nav id="navbar">
        <ul>
            <li id="nav-link"><a href="<?= base_url("/users") ?>">Users</a></li>
            <li id="nav-link"><a href="<?= base_url("/categories") ?>">Categories</a></li>
            <li id="nav-link"><a href="<?= base_url("/spents") ?>">Spents</a></li>
            <li id="nav-link"><a href="<?= base_url("/reports") ?>">Reports</a></li>
            <li id="nav-link">
                <form action="<?= site_url('/logout') ?>" method="POST">
                    <button type="submit" id="nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</header>