# Tổng Quan Về Hệ Thống

Project name: Medical Booking System

Description: Hệ thống hiển thị bác sĩ, dịch vụ cho phép người dùng đặt lịch khám, đặt lịch tư vấn. 

Author: KhanhECB

Feature:
- Quản lý bác sĩ
- Quản lý dịch vụ
- Quản lý Customer, Booking

---

## Developer

Hệ thống bao gồm mu-plugins, plugin và theme, với nhiệm vụ là:

Must Use Plugin (mu-plugin) lo phần dữ liệu cốt lõi, sử dụng kiến trúc Layer Architecture
- Dùng CPT để định nghĩa các domain
  - **doctor**: Thông tin của Bác sĩ
  - **service**: Thông tin của dịch vụ khám bệnh
  - **patient**: dữ liệu thông tin khách hàng
  - **booking**: dữ liệu nhận một booking
- Dùng Tax để phân loại dữ liệu:
  - **Chuyên khoa**: là chuyên khoa của bác sĩ và cũng là phong ban
  - **Vị trí/ Học hàm/ Học vị**: Bác sĩ, thạc sĩ, chuyên khoa 1, chuyên khoa 2,...
- Admin quản lý quan trọng
  - Quản lý Cache dữ liệu
  - Tạo Report báo cáo
  - Quản lý Customer
  - Quản lý Queue về gửi Email, gửi Sheet
- Các Field dùng Plugin Advanced Custom Fields để đăng ký
- Có lưu Cache ở lớp dữ liệu khi Query.
- Sử dụng JSON để đăng ký CPT, ACF, Taxonomy.

Plugins: Đây là các chức năng viết thêm
- Form FE:
  - Form đăng ký khám bệnh
  - Form tư vấn
  - Form truy vấn dữ liệu
- Custom Login Wordpress
  - Các setting nằm ở `Admin Page -> Setting -> Login Page`
  - Có CSS/JS ở setting
- Import Demo
  - Import data mẫu để test thử hệ thống
  - Xóa dữ liệu demo

Theme: Template hiển thị dữ liệu, đọc dữ liệu từ dưới lên