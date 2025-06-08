<?php

return [
    'components' => [
        'breadcrumbs' => [
            'home' => 'Trang chủ',
        ],
    ],

    'pages' => [
        'auth' => [
            'login' => [
                'title' => 'Đăng nhập',
                'heading' => 'Đăng nhập vào tài khoản',
                'form' => [
                    'email' => [
                        'label' => 'Địa chỉ email',
                    ],
                    'password' => [
                        'label' => 'Mật khẩu',
                    ],
                    'remember' => [
                        'label' => 'Ghi nhớ đăng nhập',
                    ],
                    'actions' => [
                        'authenticate' => [
                            'label' => 'Đăng nhập',
                        ],
                    ],
                ],
                'messages' => [
                    'failed' => 'Thông tin đăng nhập không chính xác.',
                ],
                'notifications' => [
                    'throttled' => [
                        'title' => 'Quá nhiều lần thử đăng nhập',
                        'body' => 'Vui lòng thử lại sau :seconds giây.',
                    ],
                ],
            ],

            'password_reset' => [
                'request' => [
                    'title' => 'Quên mật khẩu',
                    'heading' => 'Quên mật khẩu?',
                    'form' => [
                        'email' => [
                            'label' => 'Địa chỉ email',
                        ],
                        'actions' => [
                            'request' => [
                                'label' => 'Gửi email',
                            ],
                        ],
                    ],
                    'notifications' => [
                        'throttled' => [
                            'title' => 'Quá nhiều yêu cầu',
                            'body' => 'Vui lòng thử lại sau :seconds giây.',
                        ],
                    ],
                ],

                'reset' => [
                    'title' => 'Đặt lại mật khẩu',
                    'heading' => 'Đặt lại mật khẩu',
                    'form' => [
                        'email' => [
                            'label' => 'Địa chỉ email',
                        ],
                        'password' => [
                            'label' => 'Mật khẩu',
                        ],
                        'password_confirmation' => [
                            'label' => 'Xác nhận mật khẩu',
                        ],
                        'actions' => [
                            'reset' => [
                                'label' => 'Đặt lại mật khẩu',
                            ],
                        ],
                    ],
                ],
            ],

            'register' => [
                'title' => 'Đăng ký',
                'heading' => 'Tạo tài khoản',
                'form' => [
                    'name' => [
                        'label' => 'Họ và tên',
                    ],
                    'email' => [
                        'label' => 'Địa chỉ email',
                    ],
                    'password' => [
                        'label' => 'Mật khẩu',
                    ],
                    'password_confirmation' => [
                        'label' => 'Xác nhận mật khẩu',
                    ],
                    'actions' => [
                        'register' => [
                            'label' => 'Đăng ký',
                        ],
                    ],
                ],
            ],

            'email_verification' => [
                'title' => 'Xác thực email',
                'heading' => 'Xác thực địa chỉ email',
                'actions' => [
                    'resend_notification' => [
                        'label' => 'Gửi lại',
                    ],
                ],
                'messages' => [
                    'notification_not_received' => 'Không nhận được email chúng tôi đã gửi?',
                    'notification_sent' => 'Chúng tôi đã gửi email đến :email với hướng dẫn xác thực địa chỉ email của bạn.',
                ],
                'notifications' => [
                    'notification_resent' => [
                        'title' => 'Email đã được gửi lại',
                    ],
                    'notification_resend_throttled' => [
                        'title' => 'Quá nhiều lần gửi lại',
                        'body' => 'Vui lòng thử lại sau :seconds giây.',
                    ],
                ],
            ],
        ],
    ],

    'resources' => [
        'pages' => [
            'create_record' => [
                'title' => 'Tạo :label',
                'breadcrumb' => 'Tạo',
            ],

            'edit_record' => [
                'title' => 'Chỉnh sửa :label',
                'breadcrumb' => 'Chỉnh sửa',
            ],

            'list_records' => [
                'title' => ':label',
                'breadcrumb' => 'Danh sách',
            ],
        ],
    ],

    'user_menu' => [
        'account' => [
            'label' => 'Tài khoản',
        ],

        'logout' => [
            'label' => 'Đăng xuất',
        ],
    ],

    'widgets' => [
        'account' => [
            'heading' => 'Chào mừng trở lại',
        ],
    ],
];
