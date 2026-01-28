<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCampus | Gradebook Master-Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        
        .student-item.active {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
        }

        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border-left-color: #3b82f6;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-800 flex h-screen overflow-hidden">

    <!-- Sidebar (Global Navigation) -->
    <aside class="w-20 bg-slate-900 text-white flex flex-col items-center py-6 gap-8 shrink-0">
        <div class="bg-blue-600 p-3 rounded-xl shadow-lg shadow-blue-500/30">
            <i class="fas fa-graduation-cap text-xl"></i>
        </div>
        <nav class="flex flex-col gap-6 text-slate-400">
            <a href="dashboard.php" class="hover:text-white transition-colors"><i class="fas fa-th-large text-xl"></i></a>
            <a href="#" class="text-blue-400"><i class="fas fa-user-graduate text-xl"></i></a>
            <a href="#" class="hover:text-white transition-colors"><i class="fas fa-boxes text-xl"></i></a>
            <a href="#" class="hover:text-white transition-colors"><i class="fas fa-cog text-xl"></i></a>
        </nav>
    </aside>

    <!-- Student Selection Sidebar -->
    <section class="w-80 bg-white border-r border-slate-200 flex flex-col shrink-0">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold mb-4">Student Directory</h2>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
                <input type="text" id="studentSearch" onkeyup="filterStudents()" placeholder="Search name or ID..." 
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div id="studentList" class="flex-1 overflow-y-auto">
            <!-- Student items will be injected here by JavaScript -->
        </div>
    </section>

    <!-- Main Detail View (Gradebook) -->
    <main class="flex-1 flex flex-col bg-slate-50 overflow-hidden">
        <!-- Detail Header -->
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <img id="detailImg" src="" class="w-12 h-12 rounded-full border-2 border-slate-100 object-cover">
                <div>
                    <h1 id="detailName" class="text-xl font-bold">Select a Student</h1>
                    <p id="detailId" class="text-xs text-slate-400 font-mono">Loading records...</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-200">
                    <i class="fas fa-file-pdf mr-2"></i>Report Card
                </button>
                <button onclick="saveGrades()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-md">
                    <i class="fas fa-save mr-2"></i>Save Progress
                </button>
            </div>
        </header>

        <!-- Gradebook Content -->
        <div id="gradebookContent" class="flex-1 overflow-y-auto p-8 opacity-0 transition-opacity duration-300">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-7xl mx-auto">
                
                <!-- Grade Entry Section -->
                <div class="xl:col-span-8 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
                            <h3 class="font-bold text-sm uppercase tracking-wider text-slate-500">Academic Scores</h3>
                            <button onclick="addGradeRow()" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                <i class="fas fa-plus-circle"></i> Add Component
                            </button>
                        </div>
                        <table class="w-full text-left" id="gradeTable">
                            <thead class="text-[11px] font-bold text-slate-400 bg-slate-50 uppercase tracking-tighter">
                                <tr>
                                    <th class="px-6 py-3">Task Name</th>
                                    <th class="px-6 py-3">Score</th>
                                    <th class="px-6 py-3">Max</th>
                                    <th class="px-6 py-3">Weight</th>
                                    <th class="px-6 py-3 text-right"></th>
                                </tr>
                            </thead>
                            <tbody id="gradeEntries" class="divide-y divide-slate-50">
                                <!-- Grades injected here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h4 class="text-xs font-bold text-slate-400 uppercase mb-4">Calculated Performance</h4>
                            <div class="flex items-end gap-2">
                                <span id="currentGpa" class="text-5xl font-black text-blue-600 leading-none">0.00</span>
                                <span class="text-lg font-bold text-slate-300 mb-1">GPA</span>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h4 class="text-xs font-bold text-slate-400 uppercase mb-4">Attendance Rate</h4>
                            <div class="flex items-center gap-4">
                                <div class="flex-1 bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div id="attendanceBar" class="bg-emerald-500 h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                                <span id="attendanceVal" class="text-xl font-bold">0%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Insight Sidebar -->
                <div class="xl:col-span-4 space-y-6">
                    <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <i class="fas fa-brain text-6xl"></i>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Gemini AI Advisor</h3>
                        <p class="text-slate-400 text-xs mb-6">Analyze performance trends and generate pedagogical suggestions.</p>
                        
                        <button onclick="generateAIInsight()" class="w-full py-3 bg-blue-600 rounded-xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-blue-500 transition-all">
                            <span>✨ Run Analysis</span>
                        </button>

                        <div id="aiResponse" class="mt-6 hidden animate-in fade-in slide-in-from-bottom-2">
                            <div id="aiLoading" class="flex flex-col items-center py-4 space-y-2 hidden">
                                <div class="spinner border-left-color-white"></div>
                                <p class="text-[10px] text-slate-500">Processing scores...</p>
                            </div>
                            <div id="aiText" class="text-sm text-slate-300 italic leading-relaxed bg-white/5 p-4 rounded-xl border border-white/10">
                                <!-- AI Text -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="flex-1 flex flex-col items-center justify-center text-slate-400">
            <i class="fas fa-user-graduate text-6xl mb-4 opacity-20"></i>
            <p class="text-lg font-medium">Select a student from the directory to manage grades</p>
        </div>
    </main>

    <script>
        const apiKey = ""; // Set at runtime
        
        const students = [
            { id: "SC-101", name: "Michael Chen", dept: "Computer Science", img: "https://i.pravatar.cc/150?u=1", attendance: 92, grades: [
                { task: "Lab Task 01", score: 95, weight: 10 },
                { task: "Midterm Exam", score: 82, weight: 30 },
                { task: "Project Alpha", score: 88, weight: 20 }
            ]},
            { id: "SC-102", name: "Sarah Jenkins", dept: "Business Admin", img: "https://i.pravatar.cc/150?u=2", attendance: 25, grades: [
                { task: "Case Study 1", score: 45, weight: 15 },
                { task: "Economics Quiz", score: 55, weight: 10 },
                { task: "Midterm", score: 32, weight: 30 }
            ]},
            { id: "SC-103", name: "David Miller", dept: "Engineering", img: "https://i.pravatar.cc/150?u=3", attendance: 88, grades: [
                { task: "Physics Lab", score: 90, weight: 15 },
                { task: "Calculus II", score: 85, weight: 25 }
            ]},
            { id: "SC-104", name: "Emily Wong", dept: "Design", img: "https://i.pravatar.cc/150?u=4", attendance: 100, grades: [
                { task: "UI/UX Sketch", score: 98, weight: 20 },
                { task: "Visual Theory", score: 92, weight: 20 }
            ]}
        ];

        let activeStudent = null;

        function init() {
            renderStudentList();
        }

        function renderStudentList(filter = "") {
            const list = document.getElementById('studentList');
            list.innerHTML = "";
            
            students.filter(s => s.name.toLowerCase().includes(filter.toLowerCase()) || s.id.includes(filter))
            .forEach(s => {
                const item = document.createElement('div');
                item.className = `student-item p-4 cursor-pointer hover:bg-slate-50 border-b border-slate-50 transition-all flex items-center gap-3 ${activeStudent?.id === s.id ? 'active' : ''}`;
                item.onclick = () => selectStudent(s);
                item.innerHTML = `
                    <img src="${s.img}" class="w-10 h-10 rounded-full border border-slate-100">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">${s.name}</p>
                        <p class="text-[10px] text-slate-400 font-mono uppercase">${s.id} • ${s.dept}</p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                `;
                list.appendChild(item);
            });
        }

        function filterStudents() {
            const val = document.getElementById('studentSearch').value;
            renderStudentList(val);
        }

        function selectStudent(student) {
            activeStudent = student;
            renderStudentList(document.getElementById('studentSearch').value);
            
            document.getElementById('emptyState').classList.add('hidden');
            const content = document.getElementById('gradebookContent');
            content.classList.remove('opacity-0');
            content.classList.add('opacity-100');

            document.getElementById('detailName').innerText = student.name;
            document.getElementById('detailId').innerText = `ID: ${student.id} | Department of ${student.dept}`;
            document.getElementById('detailImg').src = student.img;

            // Load Grades
            renderGrades();
            
            // Update Attendance
            document.getElementById('attendanceBar').style.width = student.attendance + "%";
            document.getElementById('attendanceVal').innerText = student.attendance + "%";
            document.getElementById('attendanceVal').className = `text-xl font-bold ${student.attendance < 50 ? 'text-red-500' : 'text-slate-800'}`;

            // Reset AI Panel
            document.getElementById('aiResponse').classList.add('hidden');
        }

        function renderGrades() {
            const container = document.getElementById('gradeEntries');
            container.innerHTML = "";
            activeStudent.grades.forEach((g, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-3"><input type="text" value="${g.task}" onchange="updateScore(${index}, 'task', this.value)" class="w-full bg-transparent text-sm font-medium focus:text-blue-600 focus:outline-none"></td>
                    <td class="px-6 py-3"><input type="number" value="${g.score}" onchange="updateScore(${index}, 'score', this.value)" class="w-12 p-1 border rounded text-xs text-center font-bold"></td>
                    <td class="px-6 py-3 text-slate-400 text-xs">100</td>
                    <td class="px-6 py-3"><input type="number" value="${g.weight}" onchange="updateScore(${index}, 'weight', this.value)" class="w-12 p-1 border rounded text-xs text-center"></td>
                    <td class="px-6 py-3 text-right"><button onclick="deleteGrade(${index})" class="text-slate-300 hover:text-red-500"><i class="fas fa-trash-alt"></i></button></td>
                `;
                container.appendChild(row);
            });
            calculateGPA();
        }

        function updateScore(index, field, val) {
            activeStudent.grades[index][field] = field === 'task' ? val : parseFloat(val);
            calculateGPA();
        }

        function deleteGrade(index) {
            activeStudent.grades.splice(index, 1);
            renderGrades();
        }

        function addGradeRow() {
            if (!activeStudent) return;
            activeStudent.grades.push({ task: "New Component", score: 0, weight: 0 });
            renderGrades();
        }

        function calculateGPA() {
            let totalWeighted = 0;
            let totalWeight = 0;
            activeStudent.grades.forEach(g => {
                totalWeighted += (g.score * (g.weight / 100));
                totalWeight += g.weight;
            });
            const rawScore = totalWeight > 0 ? (totalWeighted / (totalWeight / 100)) : 0;
            const gpa = (rawScore / 25).toFixed(2);
            document.getElementById('currentGpa').innerText = gpa;
        }

        async function generateAIInsight() {
            if (!activeStudent) return;
            
            const responseDiv = document.getElementById('aiResponse');
            const loading = document.getElementById('aiLoading');
            const aiText = document.getElementById('aiText');

            responseDiv.classList.remove('hidden');
            loading.classList.remove('hidden');
            aiText.classList.add('hidden');

            const scoresText = activeStudent.grades.map(g => `${g.task}: ${g.score}/100`).join(", ");
            const prompt = `Student: ${activeStudent.name}. Attendance: ${activeStudent.attendance}%. Scores: ${scoresText}.
            Provide a 2-3 sentence academic analysis. Compare their scores to their attendance. Is there a gap? Suggest a specific teaching strategy. Keep it professional. No markdown bolding.`;

            try {
                const text = await callGemini(prompt);
                aiText.innerText = text;
                loading.classList.add('hidden');
                aiText.classList.remove('hidden');
            } catch (err) {
                aiText.innerText = "Connection error. AI Advisor could not be reached.";
                loading.classList.add('hidden');
                aiText.classList.remove('hidden');
            }
        }

        async function callGemini(prompt, retries = 5, delay = 1000) {
            const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;
            const payload = {
                contents: [{ parts: [{ text: prompt }] }],
                systemInstruction: { parts: [{ text: "You are a university academic counselor. Analyze student data and give brief, expert pedagogical feedback." }] }
            };

            for (let i = 0; i < retries; i++) {
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    if (!response.ok) throw new Error();
                    const json = await response.json();
                    return json.candidates?.[0]?.content?.parts?.[0]?.text;
                } catch (e) {
                    if (i === retries - 1) throw e;
                    await new Promise(r => setTimeout(r, delay));
                    delay *= 2;
                }
            }
        }

        function saveGrades() {
            // Mocking a Laravel Save
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Saved!';
                setTimeout(() => btn.innerHTML = originalText, 2000);
            }, 1000);
        }

        init();
    </script>
</body>
</html>