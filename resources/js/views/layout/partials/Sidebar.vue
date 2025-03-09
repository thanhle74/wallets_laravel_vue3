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
<script setup lang="ts">
</script>
