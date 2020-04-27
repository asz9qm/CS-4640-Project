window.onload =updateStats(); //initial update on the page
function updateStats(){
    //update Stats on each course change
    var xhr = new XMLHttpRequest(); //create XMLHttpRequest Object
    xhr.onload = function() {
        if (xhr.status == 200){
            var stats = JSON.parse(xhr.responseText);
            
            document.getElementById("generalReq").innerHTML="General Requirements: "+stats["General"];
            document.getElementById("computingReq").innerHTML="Computing Requirements: "+stats["Computing"];
            document.getElementById("integrationReq").innerHTML="Integration Requirements: "+stats["Integration"];
            document.getElementById("collegeReq").innerHTML="College Requirements: "+stats["College"];
            
            var gpaCalc = stats['GPA']/stats['credits'];
            document.getElementById("gpa").innerHTML="GPA: "+ gpaCalc.toFixed(2);
        }
    };

    xhr.open('GET', 'liveScheduleUpdate.php', true);
    xhr.send(null);
}


