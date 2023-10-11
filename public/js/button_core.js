
// hamburger menu for smartphone
let buttonToggle = document.querySelector('.button-responsive');
let header =document.querySelector('.header-nav');
let statusButton = false;
    buttonToggle.addEventListener("click",function(){
        console.log("clicked");
        if(statusButton){
            statusButton = false;
            header.setAttribute("id","navbar-inactive");
        }
        else{
            statusButton=true;
            header.setAttribute("id","navbar-active");
        }
        
        // header.classList.toggle('active');
    })
// hamburger menu for smartphone


// eye password

// eye password