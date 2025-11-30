<template>
    <div>
        <h1 class="text-3xl font-bold underline">Hello World!</h1>
    </div>
    <nav>
        <div>
            <router-link to="/">Home</router-link>
            <router-link to="/about">About</router-link>
        </div>
    </nav>
    <main>
        <router-view />
    </main>
</template>