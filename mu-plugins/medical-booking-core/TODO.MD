# Todo List

## Tìm hiểu thắc mắc lý thuyết
- [yes] Nghiên cứu cơ chế REST API


## Bootstrap
- [Yes] Thêm Option để quản lý DB đã Installed chưa
- [] 

## Infrastructure
- [yes] Tạo data option là dữ liệu CPT của Tour để người dùng chọn
- [] Tìm cách giải quyết cập nhật tự động Database ở bảng Index.
- [] Giải quyết vấn đề khóa ngoại và liên kết bảng
- 

## Repository
- [Yes] Thêm Repository cho BookingIndex, BookingMeta, TourScheduler
- [Yes] Add - Method Filter Tour nâng cao, lọc theo ID của các Tax liên quan 
- [] 

## Config
- [yes] Add Enum Status
- []
- []


## Presentation
- [] Tạo Form Shortcode cho Search CPT Tour, có auto Completed
- [] Tạo Form đặt lịch du lịch:
  - [yes] Sử dụng CF7
  - [yes] Multi step (contact, booking info, note)
  - [yes] Sử dụng Data option
  - [] Sử dụng handle CF7
  - [] Lưu vào DB
  - [] Send Email Notification 
- [yes] Tạo REST API lưu toàn bộ Tour Entity
- [] Tạo Form Filter:
  - Xắp xếp theo giá
  - Chọn điểm đến
  - Chọn phương tiện duy chuyển
  - Chọn số lượng người tối đa

## Refactor
- [yes] Sửa Namespace hệ thống
- [yes] Đổi folder để Config tập trung trong inc/Config

## Theme 
- [] Sử dụng Theme Flatsome để build Page
- [] Tạo child theme để code hiển thị Post Type Tour (single-tour) và archive-tour hoặc thử giải pháp FlatSome thử
- [] Lấy Shortcode ra hiển thị Post Type Tour

## Nghiệp vụ
- [yes] Export Post Type bằng CSV
- [yes] Design Form:
  - Contact cơ bản
  - Booking Travel
  - Feedback hoặc thắc mắc

## Taxonomy
- []
- []
- []
- []
- []

## Section Design
- [] Hero Section (Search)
- [] Tour Section (Tập hợp các Tour ở archive-tour)
- [] Feature Tour Section 
- [] Điểm đến Section
- [] FAQ Section 

## Đang làm dang dở:
- Class `AdvancedSearchTourRestShortcode` đang thiếu Enqueue Script



## Temp todo
- [] Fix lại shortcode để hiển thị
- [] Thêm Form hiển thị
- [] Refactor Import, xóa tất cả Post Cũ và Import Taxonomy theo Name
- [] Cache lại kết quả Filter
- [] Có cần Log ra các Function thường xuyên chạy chăng ?
