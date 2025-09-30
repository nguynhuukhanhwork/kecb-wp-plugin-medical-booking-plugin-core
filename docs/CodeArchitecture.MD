# Project Name: Medical Booking Core
Author: KhanhECB

## Architecture
**Kiến trúc**: Multi-Layer Architecture

**Tại sao chọn kiến trúc**: Kiến trúc này là một kiến trúc dễ dàng triển khai, nhanh chóng với Project cá nhân thì nó hoàn toàn phù hợp. 

**Đặc điểm kiến trúc** Kiến trúc này có hiệu năng không cao nhưng ưu điểm của nó là ràng buộc dữ liệu chặt chẽ. Với các tầng:
- Presentation: Là tầng biểu diễn, dùng để trình bày dữ liệu
- Application: Là nơi giao tiếp giữa tầng Presentation và Domain
- Domain (Business Logic): Đây là tầng nghiệp vụ và Logic doanh nghiệp
- Infrastructure: Hạ tầng của hệ thống, gắn chặt với Database.

