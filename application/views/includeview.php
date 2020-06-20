<head>
    <style>
        body{
            background:#C3CAA0;
        }
        .button {
            background-color: #3A6053;
            border: none;
            color: white;
            padding: 13px 28px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 3px 3px;
            cursor: pointer;
        }
        .sidenav {
                height: 100%;
                position: fixed;
                z-index: 1;
                top: 0;
                left: 0;
                background-color: #111;
                overflow-x: hidden;
                padding-top: 20px;
                
            } 
            #logout{
                float:right!important;
                
            }
            .sidenav a, .dropdown-btn {
                padding: 6px 8px 6px 40px;
                text-decoration: none;
                font-size: 20px;
                color: #818181;
                display: block;
                border: none;
                background: none;
                width: 100%;
                text-align: left;
                cursor: pointer;
                outline: none;
            }
            .main {
                margin-left: 250px;
                font-size: 20px;
                padding: 0px 10px;

            }    
            .main table{
                background:white;
            }       
            ul{
                list-style-type: none!important;

            }
            .sidenav .dropdown-btn {
                padding: 20px 30px 30px 30px;
                text-decoration: none;
                font-size: 20px;
                color: #818181;
                display: block;
                border: none;
                background: none;
                width:100%;
                text-align: left;
                cursor: pointer;
                outline: none;
                }       
                .dropdown-container {
                    display: none;
                    background-color: #262626;
                    padding-left: 8px;
                }
                .fa-caret-down {
                    float: right;
                    padding-right: 8px;
                }
                .fa {
                        display: inline-block;
                        font: normal normal normal 14px/1 FontAwesome;
                        font-size: inherit;
                        text-rendering: auto;
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                }
    </style>

</head>

<body>
    <div class="sidenav">
        <button class="dropdown-btn">Student 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="http://localhost/admin/Student_controller/student">Add Student </a>
            <a href="http://localhost/admin/Student_controller/stview" >View Student</a>
        </div>
        <button class="dropdown-btn">Proffesor 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="http://localhost/admin/Professor_controller/professor/">Add Proffesor </a>
            <a href="http://localhost/admin/Professor_controller/proview">View Proffesor</a>
        </div>
        <button class="dropdown-btn">Course 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a  href="http://localhost/admin/Course_controller/course/">Add Course </a>
            <a href="http://localhost/admin/Course_controller/classview">View Course</a>
        </div>
        <button class="dropdown-btn">Class 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="http://localhost/admin/Class_controller/classtab/">Add Class </a>
            <a href="http://localhost/admin/Class_controller/classview">View Class</a>
        </div>
        <button class="dropdown-btn">subject 
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="http://localhost/admin/Subject_controller/subject/">Add subject </a>
            <a href="http://localhost/admin/Subject_controller/subjectview">View subject</a>
        </div>
        
    </div>



    <script>
    
/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}
</script> 





