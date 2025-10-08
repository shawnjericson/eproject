@extends('layouts.admin')

@section('title', 'Câu Hỏi Bảo Mật')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Câu Hỏi Bảo Mật</h1>
                    <p class="text-muted">Thiết lập câu hỏi bảo mật để khôi phục mật khẩu khi cần thiết</p>
                </div>
                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <!-- Security Questions Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-shield-alt me-2"></i>Thiết Lập Câu Hỏi Bảo Mật
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.security-questions.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Info Alert -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Lưu ý:</strong> Câu hỏi bảo mật sẽ được sử dụng để khôi phục mật khẩu khi bạn quên. 
                                    Vui lòng chọn câu hỏi và câu trả lời mà bạn có thể nhớ lâu dài.
                                </div>

                                <!-- Security Question 1 (Required) -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-question-circle me-2"></i>Câu Hỏi Bảo Mật #1 (Bắt buộc)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_question_1" class="form-label">Câu hỏi</label>
                                        <select name="security_question_1" id="security_question_1" 
                                                class="form-select @error('security_question_1') is-invalid @enderror">
                                            <option value="">Chọn câu hỏi...</option>
                                            <option value="Tên thú cưng đầu tiên của bạn là gì?" 
                                                {{ old('security_question_1', $user->security_question_1) == 'Tên thú cưng đầu tiên của bạn là gì?' ? 'selected' : '' }}>
                                                Tên thú cưng đầu tiên của bạn là gì?
                                            </option>
                                            <option value="Tên trường tiểu học của bạn là gì?" 
                                                {{ old('security_question_1', $user->security_question_1) == 'Tên trường tiểu học của bạn là gì?' ? 'selected' : '' }}>
                                                Tên trường tiểu học của bạn là gì?
                                            </option>
                                            <option value="Tên đường bạn sống khi còn nhỏ là gì?" 
                                                {{ old('security_question_1', $user->security_question_1) == 'Tên đường bạn sống khi còn nhỏ là gì?' ? 'selected' : '' }}>
                                                Tên đường bạn sống khi còn nhỏ là gì?
                                            </option>
                                            <option value="Tên của người bạn thân nhất thời thơ ấu?" 
                                                {{ old('security_question_1', $user->security_question_1) == 'Tên của người bạn thân nhất thời thơ ấu?' ? 'selected' : '' }}>
                                                Tên của người bạn thân nhất thời thơ ấu?
                                            </option>
                                            <option value="Màu sắc yêu thích của bạn là gì?" 
                                                {{ old('security_question_1', $user->security_question_1) == 'Màu sắc yêu thích của bạn là gì?' ? 'selected' : '' }}>
                                                Màu sắc yêu thích của bạn là gì?
                                            </option>
                                        </select>
                                        @error('security_question_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_answer_1" class="form-label">Câu trả lời</label>
                                        <input type="text" name="security_answer_1" id="security_answer_1" 
                                               class="form-control @error('security_answer_1') is-invalid @enderror"
                                               value="{{ old('security_answer_1', $user->security_answer_1) }}"
                                               placeholder="Nhập câu trả lời...">
                                        @error('security_answer_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Security Question 2 (Optional) -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-secondary mb-3">
                                            <i class="fas fa-question-circle me-2"></i>Câu Hỏi Bảo Mật #2 (Tùy chọn)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_question_2" class="form-label">Câu hỏi</label>
                                        <select name="security_question_2" id="security_question_2" 
                                                class="form-select @error('security_question_2') is-invalid @enderror">
                                            <option value="">Chọn câu hỏi...</option>
                                            <option value="Tên của giáo viên yêu thích nhất?" 
                                                {{ old('security_question_2', $user->security_question_2) == 'Tên của giáo viên yêu thích nhất?' ? 'selected' : '' }}>
                                                Tên của giáo viên yêu thích nhất?
                                            </option>
                                            <option value="Món ăn yêu thích của bạn là gì?" 
                                                {{ old('security_question_2', $user->security_question_2) == 'Món ăn yêu thích của bạn là gì?' ? 'selected' : '' }}>
                                                Món ăn yêu thích của bạn là gì?
                                            </option>
                                            <option value="Tên thành phố bạn sinh ra?" 
                                                {{ old('security_question_2', $user->security_question_2) == 'Tên thành phố bạn sinh ra?' ? 'selected' : '' }}>
                                                Tên thành phố bạn sinh ra?
                                            </option>
                                            <option value="Tên của bộ phim yêu thích?" 
                                                {{ old('security_question_2', $user->security_question_2) == 'Tên của bộ phim yêu thích?' ? 'selected' : '' }}>
                                                Tên của bộ phim yêu thích?
                                            </option>
                                        </select>
                                        @error('security_question_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_answer_2" class="form-label">Câu trả lời</label>
                                        <input type="text" name="security_answer_2" id="security_answer_2" 
                                               class="form-control @error('security_answer_2') is-invalid @enderror"
                                               value="{{ old('security_answer_2', $user->security_answer_2) }}"
                                               placeholder="Nhập câu trả lời...">
                                        @error('security_answer_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Security Question 3 (Optional) -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-secondary mb-3">
                                            <i class="fas fa-question-circle me-2"></i>Câu Hỏi Bảo Mật #3 (Tùy chọn)
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_question_3" class="form-label">Câu hỏi</label>
                                        <select name="security_question_3" id="security_question_3" 
                                                class="form-select @error('security_question_3') is-invalid @enderror">
                                            <option value="">Chọn câu hỏi...</option>
                                            <option value="Số điện thoại đầu tiên của bạn?" 
                                                {{ old('security_question_3', $user->security_question_3) == 'Số điện thoại đầu tiên của bạn?' ? 'selected' : '' }}>
                                                Số điện thoại đầu tiên của bạn?
                                            </option>
                                            <option value="Tên công ty đầu tiên bạn làm việc?" 
                                                {{ old('security_question_3', $user->security_question_3) == 'Tên công ty đầu tiên bạn làm việc?' ? 'selected' : '' }}>
                                                Tên công ty đầu tiên bạn làm việc?
                                            </option>
                                            <option value="Tên của ca sĩ yêu thích?" 
                                                {{ old('security_question_3', $user->security_question_3) == 'Tên của ca sĩ yêu thích?' ? 'selected' : '' }}>
                                                Tên của ca sĩ yêu thích?
                                            </option>
                                            <option value="Tên của môn thể thao yêu thích?" 
                                                {{ old('security_question_3', $user->security_question_3) == 'Tên của môn thể thao yêu thích?' ? 'selected' : '' }}>
                                                Tên của môn thể thao yêu thích?
                                            </option>
                                        </select>
                                        @error('security_question_3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="security_answer_3" class="form-label">Câu trả lời</label>
                                        <input type="text" name="security_answer_3" id="security_answer_3" 
                                               class="form-control @error('security_answer_3') is-invalid @enderror"
                                               value="{{ old('security_answer_3', $user->security_answer_3) }}"
                                               placeholder="Nhập câu trả lời...">
                                        @error('security_answer_3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Lưu Câu Hỏi Bảo Mật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
