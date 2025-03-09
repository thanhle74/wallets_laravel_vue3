<template>
    <aside>
        <div class="sidebar">
            <nav>
                <ul>
                    <li>
                        <RouterLink to="/dashboard">
                            <i class="fa-regular fa-circle"></i>
                            Dashboard
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/transaction">
                            <i class="fa-regular fa-circle"></i>
                            Transaction
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/category">
                            <i class="fa-regular fa-circle"></i>
                            Category
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink to="/wallet">
                            <i class="fa-regular fa-circle"></i>
                            Wallet
                        </RouterLink>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
<style scoped>
.sidebar {
    padding: 15px;
    li {
        margin-bottom: 1rem;
        a {
            display: block;
            padding: 12px 15px;
            transition: all .3s ease;
            border-radius: 5px;
            &:hover {
                background-color: #e1def514;
                color: #fff;
                border-radius: 5px;
            }
            &.router-link-active {
                background-color: #685dd8;
                color: #fff;
            }
        }
    }

    i {
        margin-right: 1rem;
        font-size: 1rem;
    }
}
</style>
