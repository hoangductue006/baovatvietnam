# BẢO VẬT VIỆT NAM – I01 | Laravel 12

## 1. Chức năng

- Trang chủ giới thiệu dự án và lối vào các hoạt động.
- Bộ sưu tập 15 hồ sơ mẫu; tìm kiếm, lọc theo niên đại/chất liệu và phân trang.
- Trang chi tiết riêng cho từng bảo vật, có mô tả, trường dữ liệu, nguồn và mức độ kiểm chứng.
- Trắc nghiệm, phản hồi đúng/sai, giải thích và liên kết nguồn.
- Thẻ ghi nhớ.
- Bảng điểm: khách lưu trên trình duyệt; người đăng nhập lưu trong SQLite/MySQL và xem lịch sử theo tài khoản.
- Đăng ký, đăng nhập, đăng xuất.
- Khu vực quản trị có phân quyền: thêm/sửa/xóa hồ sơ bảo vật, câu hỏi đi kèm và tải ảnh hiện vật được phép sử dụng.
- Trợ lý Gemini tùy chọn; API key chỉ nằm trên máy chủ.
- Xuất dữ liệu JSON/CSV.
- Giao diện responsive.

## 2. Cập nhật lên project đang chạy

1. Sao lưu toàn bộ thư mục `D:\\bao-vat-viet-nam`.
2. Giải nén gói ZIP này.
3. Mở thư mục `bao-vat-viet-nam` bên trong ZIP.
4. Chép `app`, `bootstrap`, `database`, `resources`, `routes`, `public`, `README.md` vào project đang dùng và chọn ghi đè.
5. **Không ghi đè** `.env`, `database/database.sqlite`, `storage/app/data/treasures.json`, `storage/app/data/sources.json` và `vendor`.

Gói không chứa `.env` để tránh ghi đè cấu hình riêng hoặc làm lộ API key. Dữ liệu JSON hiện tại được giữ lại trong thư mục `storage/app/data`.

## 3. Chạy cập nhật

Mở PowerShell tại `D:\\bao-vat-viet-nam`:

```powershell
php artisan optimize:clear
php artisan migrate
php artisan storage:link
php artisan route:list
php artisan serve
```

Nếu `storage:link` báo liên kết đã tồn tại thì có thể bỏ qua.

Đảm bảo `.env` đang có cấu hình local tương tự:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
SESSION_DRIVER=file
CACHE_STORE=file
GEMINI_API_KEY=
GEMINI_MODEL=gemini-2.5-flash
```

Giữ nguyên `APP_KEY` hiện tại. Không chia sẻ `GEMINI_API_KEY` công khai.

## 4. Tạo tài khoản quản trị

1. Mở website và vào `/dang-ky` để tạo tài khoản bằng email của bạn.
2. Quay lại PowerShell tại thư mục project.
3. Chạy lệnh, thay email bằng email vừa đăng ký:

```powershell
php artisan i01:make-admin email-cua-ban@example.com
```

4. Đăng nhập tài khoản đó, mở `/quan-tri`.
5. Quản trị viên có thể thêm/sửa/xóa bảo vật, cập nhật câu hỏi, đáp án, giải thích và tải ảnh lên.
6. Chỉ tải ảnh hiện vật khi đã có quyền công bố. Ảnh tải lên được lưu trong `storage/app/public/artifacts`; liên kết công khai thông qua `storage:link`.

Đăng ký công khai chỉ tạo tài khoản học viên. Không có tài khoản mặc định hoặc mật khẩu quản trị được nhúng trong mã nguồn.

## 5. Lưu trữ và dữ liệu

- Người dùng, quyền quản trị và lịch sử điểm của tài khoản: SQLite/MySQL.
- Hồ sơ bảo vật và câu hỏi gắn với từng hồ sơ: `storage/app/data/treasures.json`, được chỉnh sửa qua khu vực quản trị.
- Danh mục nguồn tham khảo: `storage/app/data/sources.json`.
- Điểm của khách chưa đăng nhập: LocalStorage trên trình duyệt hiện tại.
- Ảnh tải lên: `storage/app/public/artifacts`.

## 6. Hình ảnh và tính xác thực

Gói không tự chèn ảnh AI hoặc ảnh lấy tùy tiện từ Internet để giả làm ảnh hiện vật. Hiện vật cần ảnh thật, nguồn gốc rõ ràng và quyền sử dụng hợp lệ. Quản trị viên có thể tải ảnh đã được cấp phép lên từng hồ sơ.

Dữ liệu 15 bản ghi hiện có là dữ liệu khởi tạo cho ứng dụng. Trường chưa có căn cứ vẫn được ghi “chưa xác định/cần xác minh”. Trước nghiệm thu, cần đối chiếu từng bản ghi với nguồn chính thức, bổ sung nguồn trực tiếp và xác minh quyền ảnh.

## 7. Kiểm thử trước nghiệm thu

- Mở `/`, `/bao-vat`, `/bao-vat/I01-0001`.
- Tìm kiếm, lọc, chuyển trang và tải CSV/JSON.
- Đăng ký, đăng nhập, đăng xuất.
- Làm một lượt trắc nghiệm; kiểm tra điểm.
- Đăng nhập và kiểm tra điểm đã lưu trong tài khoản.
- Lật thẻ, chuyển thẻ trước/sau.
- Cấp quyền admin bằng lệnh, thêm/sửa/xóa thử một hồ sơ mẫu.
- Thử tải một ảnh đã được phép sử dụng.
- Thử Gemini sau khi cấu hình API key hợp lệ.
- Kiểm tra trên máy tính và điện thoại.

## 8. Yêu cầu

PHP 8.2+, Composer 2, Laravel 12; bật các extension Laravel cần thiết như `mbstring`, `openssl`, `pdo`, `pdo_sqlite` (nếu dùng SQLite), `sqlite3`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`.


## Nguồn ảnh và quyền sử dụng

- **Trống Ngọc Lũ**: ảnh của VuThiAnh, lấy từ Wikimedia Commons: https://commons.wikimedia.org/wiki/File:Ngoc_Lu.jpg. Trang tệp ghi rõ tác giả đã phát hành ảnh vào phạm vi công cộng (Public domain). Ảnh đang được tải trực tiếp từ Wikimedia Commons để tránh tạo bản sao không rõ nguồn.
- **Các bảo vật còn lại**: chưa tự ý lấy ảnh từ Google Images hoặc sao chép ảnh trên website bảo tàng. Giao diện giữ trạng thái minh họa/chưa có ảnh cho đến khi xác minh được giấy phép hoặc có sự cho phép của đơn vị sở hữu.

Lưu ý: nếu triển khai chính thức, nên tải ảnh được phép về máy chủ của dự án và lưu đầy đủ tên tác giả, trang nguồn, giấy phép, ngày truy cập. Ảnh từ trang bảo tàng không mặc nhiên được phép tái sử dụng chỉ vì có thể xem công khai.


## Ảnh bảo vật và nguồn ảnh

Xem `IMAGE_SOURCES.md` để biết URL ảnh, trang nguồn và ghi chú quyền sử dụng. 14/15 bản ghi có ảnh trực tuyến; Bia Điện Nam Giao đang chờ ảnh chính xác. Ảnh tải từ máy chủ nguồn nên cần Internet và cần kiểm tra quyền sử dụng trước khi triển khai công khai.
