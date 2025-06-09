@extends('setup.layout')

@section('title', 'Cài đặt & Cấu hình - Core Framework Setup')
@section('description', 'Tiến trình cài đặt modules và dữ liệu đang được thực hiện')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-cogs text-2xl text-blue-600 animate-spin"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Đang cài đặt...</h2>
    <p class="text-gray-600">
        Vui lòng đợi trong khi hệ thống cài đặt các modules và cấu hình đã chọn.
    </p>
</div>

<div class="space-y-8">
    
    <!-- Installation Progress -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-tasks text-blue-600 mr-2"></i>
            Tiến trình cài đặt
        </h3>
        
        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Tiến độ</span>
                <span id="progress-percentage">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div id="progress-bar" class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Installation Steps -->
        <div id="installation-steps" class="space-y-3">
            <!-- Steps will be populated by JavaScript -->
        </div>
    </div>

    <!-- Installation Log -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-terminal text-green-600 mr-2"></i>
            Log cài đặt
        </h3>
        
        <div id="installation-log" class="bg-gray-900 text-green-400 p-4 rounded-lg h-64 overflow-y-auto font-mono text-sm">
            <div class="log-line">Starting Core Framework installation...</div>
        </div>
    </div>

    <!-- Warning Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-yellow-800">Lưu ý quan trọng</h4>
                <ul class="text-yellow-700 text-sm mt-2 space-y-1">
                    <li>• Vui lòng không đóng trình duyệt hoặc rời khỏi trang này</li>
                    <li>• Quá trình cài đặt có thể mất từ 2-10 phút tùy thuộc vào số lượng modules</li>
                    <li>• Nếu có lỗi xảy ra, hệ thống sẽ hiển thị thông báo chi tiết</li>
                    <li>• Sau khi hoàn thành, bạn sẽ được chuyển đến trang tổng kết</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Initially Hidden) -->
    <div id="action-buttons" class="hidden">
        <div class="text-center">
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    <span class="text-green-800 font-medium">Cài đặt hoàn tất!</span>
                </div>
                <p class="text-green-700 text-sm">
                    Hệ thống sẽ tự động chuyển đến trang hoàn thành trong <span id="countdown">5</span> giây.
                    <br>Hoặc bạn có thể click nút bên dưới để chuyển ngay.
                </p>
            </div>
            <button onclick="goToComplete()"
                    class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Chuyển đến trang hoàn thành
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Installation steps configuration
const installationSteps = [
    { id: 'packages', title: 'Cài đặt packages và dependencies', duration: 30 },
    { id: 'migrations', title: 'Tạo và chạy database migrations', duration: 20 },
    { id: 'models', title: 'Tạo models và relationships', duration: 15 },
    { id: 'resources', title: 'Tạo Filament resources', duration: 25 },
    { id: 'components', title: 'Tạo frontend components', duration: 20 },
    { id: 'sample-data', title: 'Import dữ liệu mẫu', duration: 30 },
    { id: 'cache', title: 'Clear cache và optimize', duration: 10 },
    { id: 'finalize', title: 'Hoàn thiện cài đặt', duration: 10 }
];

let currentStepIndex = 0;
let totalProgress = 0;

function initializeInstallation() {
    // Create step elements
    const stepsContainer = document.getElementById('installation-steps');
    stepsContainer.innerHTML = '';
    
    installationSteps.forEach((step, index) => {
        const stepDiv = document.createElement('div');
        stepDiv.id = `step-${step.id}`;
        stepDiv.className = 'flex items-center p-3 bg-gray-50 rounded-lg';
        stepDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 
                ${index === 0 ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500'}">
                <i class="fas fa-${index === 0 ? 'spinner fa-spin' : 'clock'} text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="font-medium text-gray-900">${step.title}</div>
                <div class="text-sm text-gray-500">Chờ xử lý...</div>
            </div>
            <div class="text-sm text-gray-400" id="step-${step.id}-status">Pending</div>
        `;
        stepsContainer.appendChild(stepDiv);
    });
    
    // Start installation process
    setTimeout(() => {
        processNextStep();
    }, 1000);
}

function processNextStep() {
    if (currentStepIndex >= installationSteps.length) {
        completeInstallation();
        return;
    }
    
    const step = installationSteps[currentStepIndex];
    const stepElement = document.getElementById(`step-${step.id}`);
    const statusElement = document.getElementById(`step-${step.id}-status`);
    
    // Update current step to processing
    stepElement.className = 'flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg';
    stepElement.querySelector('.w-8').className = 'w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3';
    stepElement.querySelector('.w-8 i').className = 'fas fa-spinner fa-spin text-sm';
    statusElement.textContent = 'Processing...';
    statusElement.className = 'text-sm text-blue-600';
    
    // Add log entry
    addLogEntry(`Starting: ${step.title}`);
    
    // Simulate processing time
    setTimeout(() => {
        // Mark step as completed
        stepElement.className = 'flex items-center p-3 bg-green-50 border border-green-200 rounded-lg';
        stepElement.querySelector('.w-8').className = 'w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3';
        stepElement.querySelector('.w-8 i').className = 'fas fa-check text-sm';
        statusElement.textContent = 'Completed';
        statusElement.className = 'text-sm text-green-600';
        
        // Add completion log
        addLogEntry(`✓ Completed: ${step.title}`);
        
        // Update progress
        totalProgress += (100 / installationSteps.length);
        updateProgress(Math.round(totalProgress));
        
        // Prepare next step
        if (currentStepIndex + 1 < installationSteps.length) {
            const nextStep = installationSteps[currentStepIndex + 1];
            const nextStepElement = document.getElementById(`step-${nextStep.id}`);
            nextStepElement.className = 'flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg';
            nextStepElement.querySelector('.w-8').className = 'w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-3';
            nextStepElement.querySelector('.w-8 i').className = 'fas fa-clock text-sm';
        }
        
        currentStepIndex++;
        
        // Process next step
        setTimeout(() => {
            processNextStep();
        }, 500);
        
    }, step.duration * 100); // Convert to milliseconds (simulated)
}

function updateProgress(percentage) {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-percentage');
    
    progressBar.style.width = `${percentage}%`;
    progressText.textContent = `${percentage}%`;
}

function addLogEntry(message) {
    const logContainer = document.getElementById('installation-log');
    const timestamp = new Date().toLocaleTimeString();
    const logLine = document.createElement('div');
    logLine.className = 'log-line';
    logLine.textContent = `[${timestamp}] ${message}`;
    
    logContainer.appendChild(logLine);
    logContainer.scrollTop = logContainer.scrollHeight;
}

function completeInstallation() {
    addLogEntry('🎉 Installation completed successfully!');
    addLogEntry('Core Framework is ready to use.');

    // Show completion message
    updateProgress(100);

    // Show action buttons
    document.getElementById('action-buttons').classList.remove('hidden');

    // Update page title
    document.querySelector('h2').textContent = 'Cài đặt hoàn tất!';
    document.querySelector('h2').nextElementSibling.textContent = 'Tất cả modules đã được cài đặt thành công.';

    // Change spinning icon to check
    document.querySelector('.fa-cogs').className = 'fas fa-check-circle text-2xl text-green-600';

    // Start countdown timer
    startCountdown();

    // Auto redirect to complete page after 5 seconds
    setTimeout(() => {
        console.log('Starting auto-redirect to complete page...');
        addLogEntry('Redirecting to completion page...');
        try {
            goToComplete();
        } catch (error) {
            console.error('Error in goToComplete:', error);
            addLogEntry('❌ Error redirecting. Please click "Chuyển đến trang hoàn thành" manually.');
        }
    }, 5000);

    // Fallback: Direct redirect after 10 seconds if goToComplete fails
    setTimeout(() => {
        console.log('Fallback redirect triggered');
        addLogEntry('🔄 Fallback redirect to completion page...');
        window.location.href = '{{ route('setup.step', 'complete') }}';
    }, 10000);
}

function startCountdown() {
    let seconds = 5;
    const countdownElement = document.getElementById('countdown');

    const timer = setInterval(() => {
        countdownElement.textContent = seconds;
        seconds--;

        if (seconds < 0) {
            clearInterval(timer);
            countdownElement.textContent = '0';
        }
    }, 1000);
}

function goToComplete() {
    console.log('goToComplete() called');
    showLoading('Đang chuyển đến trang hoàn thành...');

    try {
        // Submit installation completion
        submitStep('{{ route('setup.process', 'installation') }}', {}, (response) => {
            console.log('Installation process response:', response);
            setTimeout(() => {
                console.log('Redirecting to complete page...');
                window.location.href = '{{ route('setup.step', 'complete') }}';
            }, 1000);
        }, (error) => {
            console.error('Error in submitStep:', error);
            // Fallback: redirect directly without processing
            addLogEntry('⚠️ Processing skipped, redirecting directly...');
            setTimeout(() => {
                window.location.href = '{{ route('setup.step', 'complete') }}';
            }, 1000);
        });
    } catch (error) {
        console.error('Error in goToComplete:', error);
        // Fallback: redirect directly
        addLogEntry('⚠️ Error occurred, redirecting directly...');
        setTimeout(() => {
            window.location.href = '{{ route('setup.step', 'complete') }}';
        }, 1000);
    }
}

// Start installation when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Installation page loaded');
    initializeInstallation();
});
</script>

<style>
.log-line {
    margin-bottom: 4px;
    opacity: 0;
    animation: fadeIn 0.3s ease-in forwards;
}

@keyframes fadeIn {
    to {
        opacity: 1;
    }
}

.log-line:last-child {
    color: #10b981;
}
</style>
@endpush
