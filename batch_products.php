<?php
$data = [
    'create' => [
        [
            'name' => 'Woo Single #1',
            'type' => 'simple',
            'regular_price' => '21.99',
            'virtual' => true,
            'downloadable' => true,
            'downloads' => [
                [
                    'name' => 'Woo Single',
                    'file' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/cd_4_angle.jpg'
                ]
            ],
            'categories' => [
                [
                    'id' => 11
                ],
                [
                    'id' => 13
                ]
            ],
            'images' => [
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/cd_4_angle.jpg',
                    'position' => 0
                ]
            ]
        ],
        [
            'name' => 'New Premium Quality',
            'type' => 'simple',
            'regular_price' => '21.99',
            'description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.',
            'short_description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
            'categories' => [
                [
                    'id' => 9
                ],
                [
                    'id' => 14
                ]
            ],
            'images' => [
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg',
                    'position' => 0
                ],
                [
                    'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg',
                    'position' => 1
                ]
            ]
        ]
    ],
    'update' => [
        [
            'id' => 799,
            'default_attributes' => [
                [
                    'id' => 6,
                    'name' => 'Color,
                    'option' => 'Green'
                ],
                [
                    'id' => 0,
                    'name' => 'Size',
                    'option' => 'M'
                ]
            ]
        ]
    ],
    'delete' => [
        794
    ]
];

print_r($woocommerce->post('products/batch', $data));
?>