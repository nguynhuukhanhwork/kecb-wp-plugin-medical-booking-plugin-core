# Form System

Trong hệ thống này, sử dụng các Form Contact tích hợp với Contact Form 7. Hệ thống Form bao gồm:
- Form liên hệ cơ bản (Khiếu nại, thắc mắc,...)
- Form Booking
- Form Search, Filter (Code)

## Form liên hệ
- Mục tiêu: để ở FE cho người dùng viết Contact cơ bản
- Fields:
  - Name
  - Phone
  - Email (Option)
  - Ghi chú

## Form Booking
- Mục tiêu: Để người dùng đặt lịch, lưu dữ liệu Booking vào hệ thống
- Fields:
  - Contact:
    - Tên, người đại diện
    - Phone, người đại diện
    - Phone 2 (Option) người đại diện
  - Tour
    - Select, Tên Tour
    - Select, Số người
    - Select, Ngày khởi hành
    - Select, Thời lượng
    - Select, Số người lớn
    - Select, Số trẻ em
  - Thông tin thêm: 
    - Ghi chú


## Contact Form 7 data

### Form Contact Basic

```html
<!-- Form ID: 561 -->

[text*  travel-booking-customer-name minlength:2 maxlength:25 placeholder "Họ và tên"]

[email* travel-booking-customer-email maxlength:40 placeholder "Email"]

[tel* travel-booking-customer-phone maxlength:21 placeholder "Phone"]

[textarea travel-booking-customer-note maxlength:120 placeholder "Ghi chú"]

[submit "Gửi đi"]
```


### Form Register