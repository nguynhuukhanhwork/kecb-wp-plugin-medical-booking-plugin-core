# Mô Tả Các Entity

- doctor:
  - Họ và tên
  - Email
  - Số điện thoại
  - Năm sinh
  - Chuyên khoa (Tax)
  - Chức vụ
  - Học hàm/ Học vị
  - Nơi làm việc
  - Số năm kinh nghiệm
  - Avatar (Feature Image)
- service:
  - Tên dịch vụ
  - Mô tả
  - Giá
  - Chuyên Khoa (Taxonomy)
  - Thời lượng - duration
  - Status (active/inactive)
  - created_at (Thống kê)
- booking (Custom Table)
  - Patient ID
  - Doctor ID
  - Service ID
  - Type: Tư vấn, Đặt lịch khám
  - Ngày/Tháng (appointment_time)
  - Ghi chú
  - status (pending / confirmed / completed / cancelled)
  - created_at / updated_at (Lưu thời gian booking)
- Patient
  - Tên
  - Email
  - Số điện thoại
  - Ngày khám
  - gender (Tax)
  - address
  - created_at

## Taxonomy

### Specialty (Chuyên khoa)
- Giúp lọc bác sĩ hoặc dịch vụ theo chuyên khoa.
- Có thể dùng cho menu, trang tìm kiếm, bộ lọc.
- Cực kỳ quan trọng trong domain y tế (Medical Domain Taxonomy chính).

Ví dụ:
- Nội khoa
- Ngoại khoa
- Tai - Mũi - Họng
- Da liễu
- Răng - Hàm - Mặt
- Tim mạch

### Degree (Học hàm / học vị)
GS, PGS, TS, ThS, BSCKII, BSCKI

### position (Chức vụ)

- Trưởng khoa
- Phó khoa
- Bác sĩ điều trị
- Cố vấn chuyên môn

### Gender (Giới tính)
- Nam / Nữ /Khác
Không chỉ lưu giới tính, mà còn phục vụ cho việc thống kê, phân tích (thông qua report).

### Service Type

1. Khám tổng quát
2. Khám chuyên khoa
3. Xét nghiệm
4. Chẩn đoán hình ảnh
5. Tư vấn trực tuyến



