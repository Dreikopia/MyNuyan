<nav class="fixed bottom-2 left-2 right-2 z-50 border-t shadow-lg bg-base-300 border-none md:hidden rounded-full">
    <div class="grid grid-cols-5 h-16 max-w-md mx-auto">

        <x-resident.nav-link route="home" pattern="home.*" icon="house" label="Home" />
        <x-resident.nav-link route="news" pattern="news.*" icon="news" label="News" />
        <x-resident.nav-link route="complaints.create.category" pattern="complaints.*" icon="write" label="Report" />
        <x-resident.nav-link route="hotlines" pattern="hotline.*" icon="alert" label="Hotlines" />
        <x-resident.nav-link route="profile" pattern="profile.*" icon="profile" label="Profile" />

    </div>
</nav>
