<nav class="fixed top-0 left-0 z-50 w-full bg-[#121212]">
    <div class="grid grid-cols-3 md:flex pt-3">
        <div class="pt-3 pb-2.5 ml-5 w-20">
            <a href="index.php"><img src="images/newLogo.svg" alt="logo" class="w-50 h-7"></a>
        </div>

        <div class="flex mt-[2px] md:flex-1 justify-center">
            <form method="GET" action="#" class="flex h-9 <?php echo !isset($pageName) ? 'opacity-0' : '' ?>">
                <div class="">
                    <input type="text" value="<?php echo htmlspecialchars($searchTerm); ?>" name="search" placeholder="Search.." class="text-base mt-2 p-1.5 rounded-l h-7 bg-white w-30">
                </div>
                <button type="submit" class="text-sm cursor-pointer rounded-r mt-2 px-2 bg-[#BB86FC] hover:bg-[#A25AFB]">
                    <i class="fa fa-search relative top-[0.75px]"></i>
                </button>
            </form>
        </div>
        <div class="mt-1">
            <div class="mt-2 mr-5 ml-2 relative bottom-[2px] flex flex-row-reverse">
                <!-- If the user is logged in, show the logout button, else show the login button -->
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <a href="logout.php" class="fa-solid fa-arrow-right-from-bracket text-xl text-white"></a>
                    <div class=""> <a href="profile.php?id=<?php echo $_SESSION['id'] ?>"><img src="<?php echo htmlspecialchars($profilePicture) ?>" alt="profile picture" class="w-9 h-9 rounded-full mr-[20px] border-[1px] border-white relative bottom-1"></a></div>
                    <div class="relative right-5 top-[2px]"><a href="favourites.php"><i class="fa-solid fa-bookmark fa-xl" style="color: #bb86fc;"></i></a></div>
                <?php else : ?>
                    <a href="login.php" class="text-sm underline text-white">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>