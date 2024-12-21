<header>
    <nav>
        <ul>
            <li><a href="<?= base_url("/categories") ?>">Categories</a></li>
        </ul>
        <ul>
            <li><a href="<?= base_url("/spents") ?>">Spents</a></li>
        </ul>
        <ul>
            <li><a href="<?= base_url("/reports") ?>">Reports</a></li>
        </ul>
        <ul>
            <li>
                <form action="<?= site_url('/logout') ?>" method="POST">
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</header>