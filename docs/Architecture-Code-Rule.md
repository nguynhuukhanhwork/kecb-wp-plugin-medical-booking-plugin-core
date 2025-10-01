# Phân công nhiệm vụ của Multi-Layer Architecture

## Domain Layer 
Nhiệm vụ tầng Domain
- Đại diện cho một khái niệm trong domain (ví dụ: User, Order, Product).
- Có danh tính (Identity): thường là ID (UUID, auto-increment…).
- Có trạng thái (State): thuộc tính (name, price, email …).
- Có hành vi (Behavior): chứa logic nghiệp vụ (ví dụ Order.addItem(), User.changePassword()).
- Độc lập với hạ tầng (Persistence, UI, Database): Entity không nên chứa code SQL, ORM hay API.

## Application Layer
Folder: Dto, Service.
- Dto: Nhận các Request, gửi các Response
- Service: Xử lý Request












