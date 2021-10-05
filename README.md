# HKGN-web-assignment-server

1. Đây là phần server của project. Tất cả được hiện thực bằng PHP và kết nối với mySQL được host local

---

2. Để chạy server cần bảo đảm các bước sau:
   2.1/ install PHP 7.4, để cho đơn giản thì ta sẽ cần microsoft web installer. Trong này tìm tới phần product sẽ có PHP 7.4 x86, install cái này. Sau đó kiểm tra biến môi trường xem trong PATH đã có đường dẫn tới thư mục PHP/v7.4 chưa. Cuối cùng mởi command promt lên và gõ php -v. Nếu nó có trả version của php tức là đã thành công
   2.2/ Sau đó cài đặt composer, truy cập trang getcomposer.org và tải installer về là đơn giản nhất. Sau đó kiểm tra trong PATH đã có đường dẫn tới thư mục chứa composer chưa (thường là program data/composer/bin). Chạy lệnh composer -v để check
   2.3/ cài đặt mySQL bằng mySQL community installer

---

3. 1 số extension VS code để làm cho cuộc đời đơn giản hơn:
   3.1/ format HTML in php
   3.2/ PHP Intelephense
   3.3/ PHP Server

---

4. Khởi chạy server bằng cách chạy lệnh php -S 127.0.0.1:3000 -t public
