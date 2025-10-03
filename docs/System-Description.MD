# System Architecture Problem

[!important] Mục đích
Giải thích kiến trúc toàn hệ thống Web.
Problem và cách giải quyết (Code / Plugin / Hook )

## Tổng Quan Ý Tưởng
Tạo ra plugin + theme để có hệ thống Website Booking trong lĩnh vực Medical

Feature dự tính:
- Form Contact tư vấn: chỉ là form đơn giản, lưu dữ liệu cơ bản là Form tĩnh
- Form Contact đặt lịch khám bệnh, với các vấn đề:
    - Chọn được bác sĩ khám
    - Chọn Service để khám
    - Chọn ngày khám
    - Chọn địa chỉ khám (Giả sử có 2 chi nhánh)
- Giao diện làm bằng Elementor và Build từ đầu
- Các Entity trong hệ thống:
  - Bác sĩ (doctor): gồm các thông tin liên quan đến 1 bác sĩ (Liên hệ, học hàm/học vị, chức vụ, chuyên môn)
  - Bệnh nhân (patient): Gồm các thông tin private của bệnh nhân thu thập từ việc điên Form (Liên hệ, khám cái gì)
  - Dịch vụ (service): Là các dịch vụ khám bệnh ở tại bệnh viện (Răng hàm mặt, Khám não)
  - Đặt lịch (booking): thu thập từ Form đặt lịch tư vấn hoặc khám của bệnh nhân
- Search:
  - Tìm bác sĩ
  - Tìm Service
  - Tìm Cả 2
  - Tìm các bài viết nói về bệnh này bệnh kia, phân nhóm theo service
- Yêu cầu SEO:
  - Code chuẩn theo GG
  - Công cụ đánh giá phải đánh giá cao

## Giải pháp xử lý
- Form (Plugin + Code):
  - UI Form dùng Contact Form 7 để xử lý Front-End, gửi dữ liệu xuống DB.
  - Form Submit cần phải dùng Hook của Contact Form 7 để xử lý
  - Dữ liệu đọc ra ngoài như danh sách bác sĩ cần phải thêm Tag vào Contact Form 7
  - 
- Search (Code):
  - Lọc theo CPT để tìm cho nhanh
  - 
- Xây dựng các Entity
  - Dùng Plugin Advanced Custom Fields để tạo nhanh các meta-data liên quan đến CPT
  - 
- Hiện tại đang Code mu-plugins với kiến trúc phần mềm là Layer-Architecture cho hệ thống với nhiệm vụ xử lý các vấn đề:
  - Form Code (Tag Data Contact Form 7)
  - Tạo các Entity: Doctor + Service
  - Search

## Problem gặp phải
- Tổ chức kiến trúc có hơi lũng cũng vì chưa quen.
- Phân vân Patient và Booking nền là CPT hay là Custom Data
- Trong kiến trúc Layer thì nếu đã chọn Entity nào đó (Giả sử là doctor) thì
  - Ban đầu nên quy hoạch các Method ra sao ? 
  - Những Method nào là cần thiết, trong khi trong Plugin Advanced Custom Field đã giải quyết CRUD khá ok
- Theme:
  - Nếu dùng Elementor để Build toàn bộ, vậy có cần Bootrap không ?
  - Nếu dùng Bootrap thì có cần SCSS
  - Đâu là giải pháp ok cho Build Theme










