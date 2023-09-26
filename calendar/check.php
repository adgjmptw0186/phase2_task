<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>カレンダー</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .schedule-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="calendar-wrapper"></div>
    <table>

        <div class="wrapper">
            <!-- xxxx年xx月を表示 -->
            <h1 id="header"></h1>

            <!-- カレンダー -->
            <div id="calendar"></div>
        </div>

        <!-- ボタンクリックで月移動 -->
        <div id="next-prev-button">
            <button id="prev" onclick="prev()">‹</button>
            <button id="next" onclick="next()">›</button>
        </div>

        <div class="overlay"></div>

        <div id="scheduleForm" class="schedule-form">
            <h3 id="selectedDate"></h3>
            <textarea id="scheduleInput" rows="4" cols="50"></textarea>
            <br>
            <button onclick="saveSchedule()">Save</button>
            <button onclick="closeScheduleForm()">Cancel</button>
        </div>

        <script>
            const week = ["日", "月", "火", "水", "木", "金", "土"];
            const today = new Date();
            var showDate = new Date(today.getFullYear(), today.getMonth(), 1);
            var schedules = [];

            // 初期表示
            window.onload = function () {
                showProcess(today);
                loadSchedules();
                showSchedules();
            };

            // 前の月表示
            function prev() {
                showDate.setMonth(showDate.getMonth() - 1);
                showProcess(showDate);
                showSchedules();
            }

            // 次の月表示
            function next() {
                showDate.setMonth(showDate.getMonth() + 1);
                showProcess(showDate);
                showSchedules();
            }

            // カレンダー表示
            function showProcess(date) {
                var year = date.getFullYear();
                var month = date.getMonth();
                document.getElementById('header').innerHTML = year + "年 " + (month + 1) + "月";

                var calendar = createProcess(year, month);
                document.getElementById('calendar').innerHTML = calendar;
            }

            // カレンダー作成
            function createProcess(year, month) {
                var calendar = "<table><tr class='dayOfWeek'>";
                for (var i = 0; i < week.length; i++) {
                    calendar += "<th>" + week[i] + "</th>";
                }
                calendar += "</tr>";

                var count = 0;
                var startDayOfWeek = new Date(year, month, 1).getDay();
                var endDate = new Date(year, month + 1, 0).getDate();
                var lastMonthEndDate = new Date(year, month, 0).getDate();
                var row = Math.ceil((startDayOfWeek + endDate) / week.length);

                for (var i = 0; i < row; i++) {
                    calendar += "<tr>";
                    for (var j = 0; j < week.length; j++) {
                        if (i === 0 && j < startDayOfWeek) {
                            calendar += "<td class='disabled'>" + (lastMonthEndDate - startDayOfWeek + j + 1) + "</td>";
                        } else if (count >= endDate) {
                            count++;
                            calendar += "<td class='disabled'>" + (count - endDate) + "</td>";
                        } else {
                            count++;
                            if (year === today.getFullYear() && month === today.getMonth() && count === today.getDate()) {
                                calendar += "<td class='today' onclick='openScheduleForm(" + year + "," + (month + 1) + "," + count + ")'>" + count + "</td>";
                            } else {
                                calendar += "<td onclick='openScheduleForm(" + year + "," + (month + 1) + "," + count + ")'>" + count + "</td>";
                            }
                        }
                    }
                    calendar += "</tr>";
                }
                return calendar;
            }

            // Open schedule form
            function openScheduleForm(year, month, date) {
                var scheduleForm = document.getElementById('scheduleForm');
                var overlay = document.querySelector('.overlay');
                var selectedDate = document.getElementById('selectedDate');
                var scheduleInput = document.getElementById('scheduleInput');

                selectedDate.textContent = year + "年 " + month + "月 " + date + "日";
                scheduleInput.value = "";

                scheduleForm.style.display = 'block';
                overlay.style.display = 'block';
            }

            // Save schedule
            function saveSchedule() {
                var selectedDate = document.getElementById('selectedDate');
                var scheduleInput = document.getElementById('scheduleInput');
                var dateParts = selectedDate.textContent.split(" ");
                var year = parseInt(dateParts[0]);
                var month = parseInt(dateParts[1]);
                var date = parseInt(dateParts[2]);

                var schedule = {
                    year: year,
                    month: month,
                    date: date,
                    text: scheduleInput.value
                };

                schedules.push(schedule);
                saveSchedules();

                closeScheduleForm();
                showSchedules();
            }

            // Close schedule form
            function closeScheduleForm() {
                var scheduleForm = document.getElementById('scheduleForm');
                var overlay = document.querySelector('.overlay');
                scheduleForm.style.display = 'none';
                overlay.style.display = 'none';
            }

            // Save schedules to local storage
            function saveSchedules() {
                localStorage.setItem('schedules', JSON.stringify(schedules));
            }

            //```html
// Load schedules from local storage
            function loadSchedules() {
                var savedSchedules = localStorage.getItem('schedules');
                if (savedSchedules) {
                    schedules = JSON.parse(savedSchedules);
                }
            }

            // Show schedules on the calendar
            function showSchedules() {
                var calendarCells = document.querySelectorAll('#calendar td');
                calendarCells.forEach(function (cell) {
                    cell.innerHTML = cell.innerText; // Clear existing content
                    var date = parseInt(cell.innerText);
                    var year = showDate.getFullYear();
                    var month = showDate.getMonth() + 1;
                    var matchingSchedules = schedules.filter(function (schedule) {
                        return schedule.year === year && schedule.month === month && schedule.date === date;
                    });
                    matchingSchedules.forEach(function (schedule) {
                        var scheduleDiv = document.createElement('div');
                        scheduleDiv.classList.add('schedule');
                        scheduleDiv.innerText = schedule.text;
                        cell.appendChild(scheduleDiv);
                    });
                });
            }
			// Show schedules on the calendar
			function showSchedules() {
			var calendarCells = document.querySelectorAll('#calendar td');
			calendarCells.forEach(function (cell) {
				cell.innerHTML = cell.innerText; // Clear existing content
				var date = parseInt(cell.innerText);
				var year = showDate.getFullYear();
				var month = showDate.getMonth() + 1;
				var matchingSchedules = schedules.filter(function (schedule) {
					return schedule.year === year && schedule.month === month && schedule.date === date;
				});
				matchingSchedules.forEach(function (schedule) {
					var scheduleDiv = document.createElement('div');
					scheduleDiv.classList.add('schedule');
					scheduleDiv.innerText = schedule.text.substring(0, 3);
					cell.appendChild(scheduleDiv);
				});
			});
			}

// Save schedule
function saveSchedule() {
  var selectedDate = document.getElementById('selectedDate');
  var scheduleInput = document.getElementById('scheduleInput');
  var dateParts = selectedDate.textContent.split(" ");
  var year = parseInt(dateParts[0]);
  var month = parseInt(dateParts[1]);
  var date = parseInt(dateParts[2]);

  var scheduleValue = scheduleInput.value.trim();

  if (scheduleValue !== "") {
    var schedule = {
      year: year,
      month: month,
      date: date,
      text: scheduleValue
    };

    schedules.push(schedule);
    saveSchedules();

    closeScheduleForm();
    showSchedules();
  } else {
    alert("スケジュールを入力してください。");
  }
}


//ポップアップ画面
function showSchedules() {
  var calendarCells = document.querySelectorAll('#calendar td');
  calendarCells.forEach(function (cell) {
    cell.innerHTML = cell.innerText; // Clear existing content
    var date = parseInt(cell.innerText);
    var year = showDate.getFullYear();
    var month = showDate.getMonth() + 1;
    var matchingSchedules = schedules.filter(function (schedule) {
      return schedule.year === year && schedule.month === month && schedule.date === date;
    });
    matchingSchedules.forEach(function (schedule) {
      var scheduleDiv = document.createElement('div');
      scheduleDiv.classList.add('schedule');
      scheduleDiv.textContent = schedule.text.substring(0, 3);
      cell.appendChild(scheduleDiv);

      scheduleDiv.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent event from propagating to parent elements
        openScheduleForm(year, month, date, schedule.text);
      });
    });
  });
}

// Remove schedule
function removeSchedule(year, month, date, text) {
  var index = schedules.findIndex(function (schedule) {
    return schedule.year === year && schedule.month === month && schedule.date === date && schedule.text === text;
  });
  if (index > -1) {
    schedules.splice(index, 1);
    saveSchedules();
    showSchedules();
  }
}

// Open schedule form
function openScheduleForm(year, month, date, text) {
  var scheduleForm = document.getElementById('scheduleForm');
  var overlay = document.querySelector('.overlay');
  var selectedDate = document.getElementById('selectedDate');
  var scheduleInput = document.getElementById('scheduleInput');

  selectedDate.textContent = year + "年 " + month + "月 " + date + "日";
  scheduleInput.value = text;

  scheduleForm.style.display = 'block';
  overlay.style.display = 'block';

  // Add event listener to delete button
  var deleteButton = document.getElementById('deleteButton');
  deleteButton.addEventListener('click', function () {
    removeSchedule(year, month, date, text);
    closeScheduleForm();
  });
}

        </script>
    </table>
</body>
</html>