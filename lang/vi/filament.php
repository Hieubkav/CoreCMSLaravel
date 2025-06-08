<?php

return [
    'actions' => [
        'attach' => 'Đính kèm',
        'attach_and_detach' => 'Đính kèm & Tách',
        'attach_another' => 'Đính kèm khác',
        'cancel' => 'Hủy',
        'create' => 'Tạo mới',
        'create_and_create_another' => 'Tạo & Tạo tiếp',
        'create_another' => 'Tạo khác',
        'delete' => 'Xóa',
        'detach' => 'Tách',
        'edit' => 'Chỉnh sửa',
        'export' => 'Xuất',
        'filter' => 'Lọc',
        'import' => 'Nhập',
        'move_down' => 'Di chuyển xuống',
        'move_up' => 'Di chuyển lên',
        'replicate' => 'Sao chép',
        'save' => 'Lưu',
        'save_and_create_another' => 'Lưu & Tạo tiếp',
        'save_and_edit' => 'Lưu & Chỉnh sửa',
        'view' => 'Xem',
        'close' => 'Đóng',
        'open' => 'Mở',
        'refresh' => 'Làm mới',
        'reset' => 'Đặt lại',
        'search' => 'Tìm kiếm',
        'select_all' => 'Chọn tất cả',
        'deselect_all' => 'Bỏ chọn tất cả',
        'bulk_actions' => 'Thao tác hàng loạt',
    ],

    'components' => [
        'pagination' => [
            'label' => 'Điều hướng phân trang',
            'overview' => 'Hiển thị :first đến :last trong tổng số :total kết quả',
            'fields' => [
                'records_per_page' => [
                    'label' => 'mỗi trang',
                    'options' => [
                        'all' => 'Tất cả',
                    ],
                ],
            ],
            'actions' => [
                'go_to_page' => [
                    'label' => 'Đi đến trang :page',
                ],
                'next' => [
                    'label' => 'Tiếp theo',
                ],
                'previous' => [
                    'label' => 'Trước đó',
                ],
            ],
        ],
    ],

    'fields' => [
        'bulk_select_page' => [
            'label' => 'Chọn/bỏ chọn tất cả mục cho thao tác hàng loạt.',
        ],
        'bulk_select_record' => [
            'label' => 'Chọn/bỏ chọn mục :key cho thao tác hàng loạt.',
        ],
        'file_upload' => [
            'editor' => [
                'actions' => [
                    'cancel' => 'Hủy',
                    'drag_crop' => 'Chế độ kéo "cắt"',
                    'drag_move' => 'Chế độ kéo "di chuyển"',
                    'flip_horizontal' => 'Lật ngang',
                    'flip_vertical' => 'Lật dọc',
                    'move_down' => 'Di chuyển xuống',
                    'move_left' => 'Di chuyển trái',
                    'move_right' => 'Di chuyển phải',
                    'move_up' => 'Di chuyển lên',
                    'reset' => 'Đặt lại',
                    'rotate_left' => 'Xoay trái',
                    'rotate_right' => 'Xoay phải',
                    'save' => 'Lưu',
                    'zoom_100' => 'Phóng to 100%',
                    'zoom_in' => 'Phóng to',
                    'zoom_out' => 'Thu nhỏ',
                ],
            ],
        ],
        'rich_editor' => [
            'dialogs' => [
                'link' => [
                    'actions' => [
                        'link' => 'Liên kết',
                        'unlink' => 'Bỏ liên kết',
                    ],
                    'label' => 'URL',
                    'placeholder' => 'Nhập URL',
                ],
            ],
            'toolbar_buttons' => [
                'attach_files' => 'Đính kèm tệp',
                'blockquote' => 'Trích dẫn',
                'bold' => 'Đậm',
                'bullet_list' => 'Danh sách dấu đầu dòng',
                'code_block' => 'Khối mã',
                'h1' => 'Tiêu đề',
                'h2' => 'Tiêu đề',
                'h3' => 'Tiêu đề',
                'italic' => 'Nghiêng',
                'link' => 'Liên kết',
                'ordered_list' => 'Danh sách có số',
                'redo' => 'Làm lại',
                'strike' => 'Gạch ngang',
                'underline' => 'Gạch chân',
                'undo' => 'Hoàn tác',
            ],
        ],
        'select' => [
            'actions' => [
                'create_option' => [
                    'modal' => [
                        'heading' => 'Tạo mới',
                        'actions' => [
                            'create' => 'Tạo',
                            'create_another' => 'Tạo & tạo khác',
                        ],
                    ],
                ],
                'edit_option' => [
                    'modal' => [
                        'heading' => 'Chỉnh sửa',
                        'actions' => [
                            'save' => 'Lưu',
                        ],
                    ],
                ],
            ],
            'boolean' => [
                'true' => 'Có',
                'false' => 'Không',
            ],
            'loading_message' => 'Đang tải...',
            'max_items_message' => 'Chỉ có thể chọn :count mục.',
            'no_search_results_message' => 'Không có tùy chọn nào phù hợp với tìm kiếm của bạn.',
            'placeholder' => 'Chọn một tùy chọn',
            'searching_message' => 'Đang tìm kiếm...',
            'search_prompt' => 'Bắt đầu gõ để tìm kiếm...',
        ],
        'tags_input' => [
            'placeholder' => 'Thẻ mới',
        ],
        'text_input' => [
            'actions' => [
                'hide_password' => 'Ẩn mật khẩu',
                'show_password' => 'Hiện mật khẩu',
            ],
        ],
        'toggle' => [
            'boolean' => [
                'true' => 'Có',
                'false' => 'Không',
            ],
        ],
        'wizard' => [
            'actions' => [
                'previous_step' => 'Trước',
                'next_step' => 'Tiếp theo',
            ],
        ],
    ],

    'modal' => [
        'confirmation' => [
            'actions' => [
                'cancel' => 'Hủy',
                'confirm' => 'Xác nhận',
            ],
        ],
    ],

    'notifications' => [
        'actions' => [
            'close' => 'Đóng',
        ],
    ],

    'pages' => [
        'health_check' => [
            'buttons' => [
                'refresh' => 'Làm mới',
            ],
            'heading' => 'Kiểm tra sức khỏe ứng dụng',
            'navigation_label' => 'Kiểm tra sức khỏe',
            'notifications' => [
                'check_results' => 'Kiểm tra kết quả từ',
            ],
        ],
    ],

    'resources' => [
        'pages' => [
            'create_record' => [
                'title' => 'Tạo :label',
            ],
            'edit_record' => [
                'title' => 'Chỉnh sửa :label',
            ],
            'list_records' => [
                'title' => ':label',
            ],
        ],
    ],

    'tables' => [
        'actions' => [
            'filter' => 'Lọc',
            'open_bulk_actions' => 'Mở thao tác hàng loạt',
            'toggle_columns' => 'Chuyển đổi cột',
        ],
        'bulk_actions' => [
            'delete' => [
                'label' => 'Xóa đã chọn',
                'modal' => [
                    'heading' => 'Xóa :label đã chọn',
                    'description' => 'Bạn có chắc chắn muốn xóa :label đã chọn? Hành động này không thể hoàn tác.',
                    'actions' => [
                        'delete' => 'Xóa',
                    ],
                ],
            ],
        ],
        'columns' => [
            'text' => [
                'actions' => [
                    'collapse_list' => 'Hiển thị ít hơn :count',
                    'expand_list' => 'Hiển thị thêm :count',
                ],
            ],
        ],
        'filters' => [
            'actions' => [
                'remove' => 'Xóa bộ lọc',
                'remove_all' => 'Xóa tất cả bộ lọc',
                'reset' => 'Đặt lại',
            ],
            'indicator' => 'Bộ lọc đang hoạt động',
            'multi_select' => [
                'placeholder' => 'Tất cả',
            ],
            'select' => [
                'placeholder' => 'Tất cả',
            ],
            'trinary' => [
                'placeholder' => 'Tất cả',
                'true' => 'Có',
                'false' => 'Không',
            ],
        ],
        'grouping' => [
            'fields' => [
                'group' => [
                    'label' => 'Nhóm theo',
                    'placeholder' => 'Nhóm theo',
                ],
                'direction' => [
                    'label' => 'Hướng nhóm',
                    'options' => [
                        'asc' => 'Tăng dần',
                        'desc' => 'Giảm dần',
                    ],
                ],
            ],
        ],
        'reorder_indicator' => 'Kéo và thả các bản ghi theo thứ tự.',
        'selection_indicator' => [
            'selected_count' => '1 bản ghi đã chọn|:count bản ghi đã chọn',
            'actions' => [
                'select_all' => 'Chọn tất cả :count',
                'deselect_all' => 'Bỏ chọn tất cả',
            ],
        ],
        'sorting' => [
            'fields' => [
                'column' => [
                    'label' => 'Sắp xếp theo',
                ],
                'direction' => [
                    'label' => 'Hướng sắp xếp',
                    'options' => [
                        'asc' => 'Tăng dần',
                        'desc' => 'Giảm dần',
                    ],
                ],
            ],
        ],
    ],

    'widgets' => [
        'account' => [
            'heading' => 'Chào mừng trở lại',
        ],
        'filament_info' => [
            'actions' => [
                'open' => 'Mở',
            ],
            'description' => 'Filament là một bộ công cụ để xây dựng nhanh chóng các ứng dụng quản trị TALL stack đẹp mắt.',
            'heading' => 'Chào mừng đến với Filament',
        ],
    ],
];
