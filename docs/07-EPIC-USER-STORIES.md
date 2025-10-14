# **📋 Epic và User Stories - Global Heritage Project**

## **🎯 Epic Overview**

Dự án Global Heritage được chia thành 8 Epic chính, mỗi Epic bao gồm nhiều User Stories cụ thể để đảm bảo phát triển có hệ thống và đáp ứng đúng nhu cầu người dùng.

---

## **🌐 Epic 1: Frontend React Application**

### **Mô tả Epic**
Xây dựng ứng dụng React SPA với giao diện hiện đại, responsive và trải nghiệm người dùng tối ưu.

### **User Stories**

#### **US-FE-001: Tải Trang Nhanh Chóng**
**Là một** người dùng website  
**Tôi muốn** trang web tải nhanh chóng  
**Để** có thể bắt đầu duyệt nội dung ngay lập tức  

**Tiêu chí chấp nhận**:
- [ ] Trang tải trong vòng 3 giây
- [ ] Hero section hiển thị ngay lập tức
- [ ] Loading spinner hiển thị khi tải dữ liệu
- [ ] Hình ảnh tải dần dần

**Nhiệm vụ kỹ thuật**:
- Implement lazy loading cho hình ảnh
- Sử dụng React.lazy() cho code splitting
- Tối ưu bundle size
- Implement caching strategy

#### **US-FE-002: Điều Hướng Mượt Mà**
**Là một** người dùng website  
**Tôi muốn** điều hướng giữa các trang mượt mà  
**Để** duyệt nội dung không bị gián đoạn  

**Tiêu chí chấp nhận**:
- [ ] Chuyển trang mượt mà
- [ ] URL cập nhật chính xác
- [ ] Nút back/forward của browser hoạt động
- [ ] Trang tự động cuộn lên đầu khi chuyển trang

**Nhiệm vụ kỹ thuật**:
- Setup React Router
- Implement ScrollToTop component
- Thêm transition animations
- Xử lý browser history

#### **US-FE-003: Thiết Kế Responsive**
**Là một** người dùng mobile/tablet  
**Tôi muốn** website hoạt động tốt trên thiết bị của mình  
**Để** có thể truy cập nội dung thoải mái  

**Tiêu chí chấp nhận**:
- [ ] Layout thích ứng với kích thước màn hình
- [ ] Tương tác cảm ứng hoạt động tốt
- [ ] Văn bản dễ đọc không cần zoom
- [ ] Hình ảnh scale phù hợp

**Nhiệm vụ kỹ thuật**:
- Implement responsive CSS
- Sử dụng mobile-first design
- Test trên nhiều thiết bị
- Tối ưu touch targets

#### **US-FE-004: Chuyển Đổi Ngôn Ngữ**
**Là một** người dùng đa ngôn ngữ  
**Tôi muốn** chuyển đổi giữa Tiếng Việt và English  
**Để** đọc nội dung bằng ngôn ngữ ưa thích  

**Tiêu chí chấp nhận**:
- [ ] Công tắc ngôn ngữ hiển thị rõ ràng
- [ ] Tất cả văn bản cập nhật ngay lập tức
- [ ] Tùy chọn ngôn ngữ được ghi nhớ
- [ ] API calls bao gồm tham số ngôn ngữ

**Nhiệm vụ kỹ thuật**:
- Setup React Context cho ngôn ngữ
- Tạo component chuyển đổi ngôn ngữ
- Implement hệ thống dịch thuật
- Cập nhật API calls với ngôn ngữ

#### **US-FE-005: Danh Sách Di Tích**
**Là một** người dùng website  
**Tôi muốn** xem danh sách các di tích lịch sử  
**Để** khám phá những địa điểm thú vị  

**Tiêu chí chấp nhận**:
- [ ] Di tích hiển thị dạng grid/list
- [ ] Mỗi di tích hiển thị hình ảnh, tiêu đề, vị trí
- [ ] Phân trang hoạt động chính xác
- [ ] Tùy chọn tìm kiếm và lọc có sẵn

**Nhiệm vụ kỹ thuật**:
- Tạo MonumentCard component
- Implement pagination
- Thêm chức năng tìm kiếm
- Kết nối với monuments API

#### **US-FE-006: Chi Tiết Di Tích**
**Là một** người dùng website  
**Tôi muốn** xem thông tin chi tiết về di tích  
**Để** tìm hiểu sâu hơn về lịch sử và ý nghĩa  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị đầy đủ thông tin di tích
- [ ] Gallery hình ảnh với lightbox
- [ ] Bản đồ tương tác hiển thị vị trí
- [ ] Đề xuất di tích liên quan

**Nhiệm vụ kỹ thuật**:
- Tạo MonumentDetail component
- Implement image gallery
- Tích hợp map component
- Thêm logic nội dung liên quan

#### **US-FE-007: Bản Đồ Tương Tác**
**Là một** người dùng website  
**Tôi muốn** xem vị trí di tích trên bản đồ  
**Để** hiểu rõ bối cảnh địa lý  

**Tiêu chí chấp nhận**:
- [ ] Bản đồ tải với markers di tích
- [ ] Click marker hiển thị thông tin di tích
- [ ] Bản đồ responsive và touch-friendly
- [ ] Tìm kiếm vị trí hoạt động

**Nhiệm vụ kỹ thuật**:
- Tích hợp thư viện bản đồ (Google Maps/Leaflet)
- Tạo map component
- Thêm marker clustering
- Implement tìm kiếm vị trí

#### **US-FE-008: Thư Viện Hình Ảnh**
**Là một** người dùng website  
**Tôi muốn** xem hình ảnh di tích trong gallery  
**Để** xem chi tiết các bức ảnh  

**Tiêu chí chấp nhận**:
- [ ] Grid thumbnail hiển thị
- [ ] Click mở lightbox view
- [ ] Điều hướng giữa các hình ảnh
- [ ] Hình ảnh tải dần dần

**Nhiệm vụ kỹ thuật**:
- Tạo Gallery component
- Implement lightbox functionality
- Thêm lazy loading hình ảnh
- Tối ưu kích thước hình ảnh

#### **US-FE-009: Đọc Blog**
**Là một** người dùng website  
**Tôi muốn** đọc các bài viết blog về di sản văn hóa  
**Để** tìm hiểu thêm về chủ đề  

**Tiêu chí chấp nhận**:
- [ ] Danh sách bài viết với preview
- [ ] Nội dung bài viết đầy đủ hiển thị
- [ ] Đề xuất bài viết liên quan
- [ ] Tùy chọn chia sẻ mạng xã hội

**Nhiệm vụ kỹ thuật**:
- Tạo PostCard component
- Implement PostDetail view
- Thêm logic bài viết liên quan
- Tích hợp chia sẻ mạng xã hội

#### **US-FE-010: Tìm Kiếm Nội Dung**
**Là một** người dùng website  
**Tôi muốn** tìm kiếm nội dung cụ thể  
**Để** tìm thông tin cần thiết nhanh chóng  

**Tiêu chí chấp nhận**:
- [ ] Hộp tìm kiếm nổi bật
- [ ] Kết quả hiển thị nội dung liên quan
- [ ] Tìm kiếm hoạt động trên di tích và bài viết
- [ ] Thông báo khi không có kết quả

**Nhiệm vụ kỹ thuật**:
- Tạo Search component
- Implement search API integration
- Thêm highlight kết quả tìm kiếm
- Xử lý trường hợp không có kết quả

#### **US-FE-011: Form Liên Hệ**
**Là một** người dùng website  
**Tôi muốn** gửi tin nhắn cho quản trị viên  
**Để** liên hệ với câu hỏi  

**Tiêu chí chấp nhận**:
- [ ] Form liên hệ dễ tìm thấy
- [ ] Validation form hoạt động
- [ ] Thông báo thành công hiển thị
- [ ] Email được gửi đến admin

**Nhiệm vụ kỹ thuật**:
- Tạo ContactForm component
- Implement form validation
- Kết nối với contact API
- Thêm success/error handling

#### **US-FE-012: Gửi Phản Hồi**
**Là một** người dùng website  
**Tôi muốn** để lại phản hồi về di tích hoặc bài viết  
**Để** chia sẻ suy nghĩ của mình  

**Tiêu chí chấp nhận**:
- [ ] Form phản hồi dễ tiếp cận
- [ ] Có thể chọn di tích/bài viết để bình luận
- [ ] Validation form ngăn chặn spam
- [ ] Phản hồi hiển thị sau khi được duyệt

**Nhiệm vụ kỹ thuật**:
- Tạo FeedbackForm component
- Implement chọn nội dung
- Thêm spam prevention
- Kết nối với feedback API

#### **US-FE-013: Thông Báo Thành Công**
**Là một** người dùng website  
**Tôi muốn** thấy xác nhận khi gửi form  
**Để** biết hành động của mình thành công  

**Tiêu chí chấp nhận**:
- [ ] Thông báo thành công xuất hiện
- [ ] Thông báo rõ ràng và hữu ích
- [ ] Thông báo biến mất sau timeout
- [ ] Form reset sau khi thành công

**Nhiệm vụ kỹ thuật**:
- Tạo Notification component
- Implement toast notifications
- Thêm auto-dismiss functionality
- Xử lý form reset

#### **US-FE-014: Xem Phản Hồi**
**Là một** người dùng website  
**Tôi muốn** xem phản hồi từ người dùng khác  
**Để** đọc các quan điểm khác nhau  

**Tiêu chí chấp nhận**:
- [ ] Phản hồi hiển thị trên trang di tích/bài viết
- [ ] Phản hồi được kiểm duyệt và phù hợp
- [ ] Có thể xem số lượng phản hồi
- [ ] Phản hồi được phân trang

**Nhiệm vụ kỹ thuật**:
- Tạo FeedbackList component
- Implement pagination
- Thêm moderation display
- Kết nối với feedback API

#### **US-FE-015: Tải Hình Ảnh Nhanh**
**Là một** người dùng website  
**Tôi muốn** hình ảnh tải nhanh  
**Để** xem nội dung mà không phải chờ đợi  

**Tiêu chí chấp nhận**:
- [ ] Hình ảnh tải dần dần
- [ ] Placeholder hiển thị trong khi tải
- [ ] Hình ảnh được tối ưu cho web
- [ ] Lazy loading ngăn chặn tải không cần thiết

**Nhiệm vụ kỹ thuật**:
- Implement lazy loading hình ảnh
- Thêm loading placeholders
- Tối ưu nén hình ảnh
- Sử dụng định dạng hình ảnh hiện đại

#### **US-FE-016: Loading Indicators**
**Là một** người dùng website  
**Tôi muốn** thấy loading indicators khi tải trang  
**Để** biết hệ thống đang hoạt động  

**Tiêu chí chấp nhận**:
- [ ] Loading spinner xuất hiện khi tải dữ liệu
- [ ] Trạng thái loading rõ ràng không gây nhầm lẫn
- [ ] Loading biến mất khi nội dung sẵn sàng
- [ ] Không có nhấp nháy hoặc nhảy

**Nhiệm vụ kỹ thuật**:
- Tạo LoadingOverlay component
- Implement loading states
- Thêm smooth transitions
- Xử lý loading errors

#### **US-FE-017: Tự Động Cuộn Lên Đầu**
**Là một** người dùng website  
**Tôi muốn** trang tự động cuộn lên đầu khi điều hướng  
**Để** thấy đầu nội dung mới  

**Tiêu chí chấp nhận**:
- [ ] Trang cuộn lên đầu khi điều hướng
- [ ] Cuộn mượt mà không giật cục
- [ ] Hoạt động trên tất cả chuyển trang
- [ ] Không can thiệp vào cuộn của người dùng

**Nhiệm vụ kỹ thuật**:
- Implement ScrollToTop component
- Thêm smooth scroll behavior
- Xử lý route changes
- Test trên các thiết bị khác nhau

#### **US-FE-018: Hiệu Suất Mượt Mà**
**Là một** người dùng website  
**Tôi muốn** website chạy mượt mà  
**Để** duyệt không bị khó chịu  

**Tiêu chí chấp nhận**:
- [ ] Không lag trong tương tác
- [ ] Animations mượt mà
- [ ] Chuyển trang linh hoạt
- [ ] Không có memory leaks

**Nhiệm vụ kỹ thuật**:
- Tối ưu React rendering
- Implement proper cleanup
- Sử dụng React.memo khi phù hợp
- Profile performance

#### **US-FE-019: Chế Độ Bảo Trì**
**Là một** người dùng website  
**Tôi muốn** thấy thông báo bảo trì khi site đang cập nhật  
**Để** hiểu tại sao site không khả dụng  

**Tiêu chí chấp nhận**:
- [ ] Trang bảo trì hiển thị rõ ràng
- [ ] Thông báo giải thích tình huống
- [ ] Thời gian ước tính được cung cấp
- [ ] Thông tin liên hệ có sẵn

**Nhiệm vụ kỹ thuật**:
- Tạo Maintenance component
- Implement maintenance detection
- Thêm countdown timer
- Cung cấp tùy chọn liên hệ

#### **US-FE-020: Xử Lý Lỗi**
**Là một** người dùng website  
**Tôi muốn** thấy thông báo lỗi hữu ích  
**Để** hiểu điều gì đã xảy ra  

**Tiêu chí chấp nhận**:
- [ ] Thông báo lỗi thân thiện với người dùng
- [ ] Các lỗi khác nhau hiển thị thông báo khác nhau
- [ ] Tùy chọn thử lại được cung cấp
- [ ] Chi tiết kỹ thuật được ẩn

**Nhiệm vụ kỹ thuật**:
- Tạo ErrorBoundary component
- Implement error handling
- Thêm retry mechanisms
- Tạo thông báo thân thiện

#### **US-FE-021: Graceful Degradation**
**Là một** người dùng website  
**Tôi muốn** site vẫn hoạt động khi một số tính năng thất bại  
**Để** vẫn có thể truy cập nội dung cơ bản  

**Tiêu chí chấp nhận**:
- [ ] Nội dung cốt lõi tải ngay cả khi API thất bại
- [ ] Nội dung fallback được hiển thị
- [ ] Người dùng vẫn có thể điều hướng
- [ ] Phục hồi lỗi có thể

**Nhiệm vụ kỹ thuật**:
- Implement fallback content
- Thêm offline detection
- Tạo error recovery
- Test failure scenarios

---

## **🔧 Epic 2: CMS Admin Panel**

### **Mô tả Epic**
Hệ thống quản trị nội dung toàn diện cho phép admin và moderator quản lý tất cả khía cạnh của website.

### **User Stories**

#### **US-CMS-001: Quản Lý Danh Sách Người Dùng**
**Là một** admin  
**Tôi muốn** quản lý danh sách người dùng  
**Để** kiểm soát quyền truy cập và quản lý tài khoản  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị danh sách tất cả người dùng
- [ ] Có thể tìm kiếm người dùng theo tên/email
- [ ] Hiển thị vai trò (admin/moderator) của từng người
- [ ] Có thể xem thông tin chi tiết người dùng
- [ ] Có thể xóa người dùng với xác nhận

**Nhiệm vụ kỹ thuật**:
- Tạo trang danh sách người dùng
- Implement tìm kiếm và lọc
- Tạo modal xác nhận xóa
- Xử lý chuyển giao nội dung khi xóa

#### **US-CMS-002: Phân Quyền Moderator**
**Là một** admin  
**Tôi muốn** phân quyền moderator  
**Để** họ chỉ quản lý nội dung của mình  

**Tiêu chí chấp nhận**:
- [ ] Moderator chỉ thấy nội dung mình tạo
- [ ] Moderator không thể xem nội dung của người khác
- [ ] Moderator không thể quản lý người dùng
- [ ] Admin có thể xem tất cả nội dung

**Nhiệm vụ kỹ thuật**:
- Implement middleware phân quyền
- Tạo scope cho model queries
- Cập nhật controller logic
- Test phân quyền

#### **US-CMS-003: Chặn Đăng Ký Người Dùng**
**Là một** admin  
**Tôi muốn** chặn đăng ký người dùng mới  
**Để** kiểm soát việc tạo tài khoản  

**Tiêu chí chấp nhận**:
- [ ] Có cài đặt bật/tắt đăng ký
- [ ] Khi tắt, trang đăng ký hiển thị thông báo
- [ ] Cài đặt được lưu vào database
- [ ] Thay đổi có hiệu lực ngay lập tức

**Nhiệm vụ kỹ thuật**:
- Tạo middleware CheckRegistrationEnabled
- Cập nhật SiteSetting model
- Tạo cài đặt trong admin panel
- Test chức năng chặn đăng ký

#### **US-CMS-004: Xóa Người Dùng An Toàn**
**Là một** admin  
**Tôi muốn** xóa người dùng mà không mất dữ liệu  
**Để** bảo toàn nội dung quan trọng  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị danh sách nội dung sẽ bị ảnh hưởng
- [ ] Cho phép chọn người dùng nhận quyền sở hữu
- [ ] Mặc định chuyển cho admin nếu không chọn
- [ ] Xác nhận trước khi xóa

**Nhiệm vụ kỹ thuật**:
- Tạo modal chuyển giao nội dung
- Implement logic chuyển quyền sở hữu
- Cập nhật foreign key constraints
- Test chức năng xóa an toàn

#### **US-CMS-005: Tạo Di Tích Mới**
**Là một** admin/moderator  
**Tôi muốn** tạo mới thông tin di tích  
**Để** bổ sung dữ liệu di sản văn hóa  

**Tiêu chí chấp nhận**:
- [ ] Form tạo di tích có đầy đủ trường thông tin
- [ ] Upload nhiều hình ảnh cùng lúc
- [ ] Chọn vị trí trên bản đồ
- [ ] Lưu nháp và tiếp tục chỉnh sửa
- [ ] Tự động duyệt nếu là admin

**Nhiệm vụ kỹ thuật**:
- Tạo form tạo di tích
- Implement upload hình ảnh
- Tích hợp bản đồ
- Xử lý logic duyệt tự động

#### **US-CMS-006: Chỉnh Sửa Di Tích**
**Là một** admin/moderator  
**Tôi muốn** chỉnh sửa thông tin di tích  
**Để** cập nhật thông tin chính xác  

**Tiêu chí chấp nhận**:
- [ ] Form chỉnh sửa có dữ liệu hiện tại
- [ ] Có thể thêm/xóa hình ảnh
- [ ] Có thể cập nhật vị trí
- [ ] Lưu thay đổi và thông báo thành công

**Nhiệm vụ kỹ thuật**:
- Tạo form chỉnh sửa
- Implement cập nhật hình ảnh
- Xử lý cập nhật vị trí
- Thêm thông báo thành công

#### **US-CMS-007: Duyệt Di Tích**
**Là một** admin  
**Tôi muốn** duyệt/từ chối di tích  
**Để** kiểm soát chất lượng nội dung  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị danh sách di tích chờ duyệt
- [ ] Có thể duyệt hoặc từ chối
- [ ] Khi từ chối phải có lý do
- [ ] Thông báo cho người tạo

**Nhiệm vụ kỹ thuật**:
- Tạo trang duyệt di tích
- Implement logic duyệt/từ chối
- Tạo modal nhập lý do từ chối
- Gửi thông báo email

#### **US-CMS-008: Tìm Kiếm Và Lọc Di Tích**
**Là một** admin/moderator  
**Tôi muốn** tìm kiếm và lọc di tích  
**Để** dễ dàng quản lý nội dung  

**Tiêu chí chấp nhận**:
- [ ] Tìm kiếm theo tên di tích
- [ ] Lọc theo trạng thái (đã duyệt/chờ duyệt)
- [ ] Lọc theo tác giả (chỉ admin)
- [ ] Lọc tự động khi chọn

**Nhiệm vụ kỹ thuật**:
- Tạo form tìm kiếm và lọc
- Implement logic lọc
- Thêm JavaScript auto-filter
- Test chức năng lọc

#### **US-CMS-009: Xem Chi Tiết Di Tích**
**Là một** admin/moderator  
**Tôi muốn** xem chi tiết di tích  
**Để** kiểm tra thông tin đầy đủ  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị đầy đủ thông tin di tích
- [ ] Hiển thị tất cả hình ảnh
- [ ] Hiển thị vị trí trên bản đồ
- [ ] Có nút duyệt/từ chối/xóa

**Nhiệm vụ kỹ thuật**:
- Tạo trang chi tiết di tích
- Hiển thị hình ảnh gallery
- Tích hợp bản đồ
- Thêm các nút hành động

#### **US-CMS-010: Tạo Bài Viết Mới**
**Là một** admin/moderator  
**Tôi muốn** tạo bài viết mới  
**Để** chia sẻ thông tin về di sản văn hóa  

**Tiêu chí chấp nhận**:
- [ ] Form tạo bài viết có editor rich text
- [ ] Upload hình ảnh minh họa
- [ ] Chọn di tích liên quan
- [ ] Lưu nháp và xuất bản

**Nhiệm vụ kỹ thuật**:
- Tích hợp TinyMCE editor
- Implement upload hình ảnh
- Tạo dropdown chọn di tích
- Xử lý logic lưu nháp

#### **US-CMS-011: Chỉnh Sửa Bài Viết**
**Là một** admin/moderator  
**Tôi muốn** chỉnh sửa bài viết  
**Để** cập nhật nội dung chính xác  

**Tiêu chí chấp nhận**:
- [ ] Form chỉnh sửa có nội dung hiện tại
- [ ] Có thể thay đổi hình ảnh minh họa
- [ ] Có thể cập nhật di tích liên quan
- [ ] Lưu thay đổi và thông báo

**Nhiệm vụ kỹ thuật**:
- Tạo form chỉnh sửa bài viết
- Implement cập nhật hình ảnh
- Xử lý cập nhật di tích liên quan
- Thêm thông báo thành công

#### **US-CMS-012: Duyệt Bài Viết**
**Là một** admin  
**Tôi muốn** duyệt/từ chối bài viết  
**Để** kiểm soát chất lượng nội dung  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị danh sách bài viết chờ duyệt
- [ ] Có thể duyệt hoặc từ chối
- [ ] Khi từ chối phải có lý do
- [ ] Thông báo cho người tạo

**Nhiệm vụ kỹ thuật**:
- Tạo trang duyệt bài viết
- Implement logic duyệt/từ chối
- Tạo modal nhập lý do từ chối
- Gửi thông báo email

#### **US-CMS-013: Tìm Kiếm Và Lọc Bài Viết**
**Là một** admin/moderator  
**Tôi muốn** tìm kiếm và lọc bài viết  
**Để** dễ dàng quản lý nội dung  

**Tiêu chí chấp nhận**:
- [ ] Tìm kiếm theo tiêu đề bài viết
- [ ] Lọc theo trạng thái (đã duyệt/chờ duyệt)
- [ ] Lọc theo tác giả (chỉ admin)
- [ ] Lọc tự động khi chọn

**Nhiệm vụ kỹ thuật**:
- Tạo form tìm kiếm và lọc
- Implement logic lọc
- Thêm JavaScript auto-filter
- Test chức năng lọc

#### **US-CMS-014: Xem Chi Tiết Bài Viết**
**Là một** admin/moderator  
**Tôi muốn** xem chi tiết bài viết  
**Để** kiểm tra nội dung đầy đủ  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị đầy đủ nội dung bài viết
- [ ] Hiển thị hình ảnh minh họa
- [ ] Hiển thị di tích liên quan
- [ ] Có nút duyệt/từ chối/xóa

**Nhiệm vụ kỹ thuật**:
- Tạo trang chi tiết bài viết
- Hiển thị nội dung rich text
- Hiển thị di tích liên quan
- Thêm các nút hành động

#### **US-CMS-015: Upload Hình Ảnh**
**Là một** admin/moderator  
**Tôi muốn** upload nhiều hình ảnh cùng lúc  
**Để** tiết kiệm thời gian quản lý  

**Tiêu chí chấp nhận**:
- [ ] Chọn nhiều file cùng lúc
- [ ] Hiển thị progress upload
- [ ] Tự động resize hình ảnh
- [ ] Lưu vào thư viện chung

**Nhiệm vụ kỹ thuật**:
- Implement multi-file upload
- Tạo progress bar
- Tích hợp Cloudinary resize
- Lưu vào bảng galleries

#### **US-CMS-016: Xem Danh Sách Hình Ảnh**
**Là một** admin/moderator  
**Tôi muốn** xem danh sách tất cả hình ảnh  
**Để** quản lý thư viện hình ảnh  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị grid hình ảnh
- [ ] Có thể tìm kiếm theo tên
- [ ] Hiển thị thông tin hình ảnh
- [ ] Có thể xóa hình ảnh

**Nhiệm vụ kỹ thuật**:
- Tạo trang danh sách hình ảnh
- Implement tìm kiếm
- Hiển thị thông tin metadata
- Thêm chức năng xóa

#### **US-CMS-017: Xóa Hình Ảnh**
**Là một** admin/moderator  
**Tôi muốn** xóa hình ảnh không cần thiết  
**Để** tiết kiệm dung lượng lưu trữ  

**Tiêu chí chấp nhận**:
- [ ] Chọn nhiều hình ảnh để xóa
- [ ] Xác nhận trước khi xóa
- [ ] Xóa khỏi Cloudinary và database
- [ ] Thông báo kết quả xóa

**Nhiệm vụ kỹ thuật**:
- Implement multi-select
- Tạo modal xác nhận
- Xóa từ Cloudinary và database
- Thêm thông báo kết quả

#### **US-CMS-018: Sắp Xếp Hình Ảnh**
**Là một** admin/moderator  
**Tôi muốn** sắp xếp hình ảnh theo di tích  
**Để** tổ chức thư viện có hệ thống  

**Tiêu chí chấp nhận**:
- [ ] Lọc hình ảnh theo di tích
- [ ] Hiển thị hình ảnh của di tích cụ thể
- [ ] Có thể gán hình ảnh cho di tích
- [ ] Có thể bỏ gán hình ảnh

**Nhiệm vụ kỹ thuật**:
- Tạo dropdown lọc theo di tích
- Implement gán/bỏ gán hình ảnh
- Cập nhật quan hệ trong database
- Test chức năng sắp xếp

#### **US-CMS-019: Xem Phản Hồi Mới**
**Là một** admin/moderator  
**Tôi muốn** xem danh sách phản hồi mới  
**Để** theo dõi ý kiến người dùng  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị badge số lượng phản hồi mới
- [ ] Danh sách phản hồi chưa xem
- [ ] Có thể đánh dấu đã xem
- [ ] Có thể đánh dấu tất cả đã xem

**Nhiệm vụ kỹ thuật**:
- Tạo badge thông báo
- Implement đánh dấu đã xem
- Tạo API đánh dấu tất cả
- Test chức năng thông báo

#### **US-CMS-020: Đánh Dấu Phản Hồi Đã Xem**
**Là một** admin/moderator  
**Tôi muốn** đánh dấu phản hồi đã xem  
**Để** theo dõi trạng thái xử lý  

**Tiêu chí chấp nhận**:
- [ ] Tự động đánh dấu khi xem chi tiết
- [ ] Có thể đánh dấu thủ công
- [ ] Có thể đánh dấu tất cả cùng lúc
- [ ] Badge cập nhật theo thời gian thực

**Nhiệm vụ kỹ thuật**:
- Implement auto-mark khi xem
- Tạo nút đánh dấu thủ công
- Tạo nút đánh dấu tất cả
- Cập nhật badge real-time

#### **US-CMS-021: Xem Tin Nhắn Liên Hệ**
**Là một** admin/moderator  
**Tôi muốn** xem tin nhắn liên hệ từ người dùng  
**Để** phản hồi kịp thời  

**Tiêu chí chấp nhận**:
- [ ] Danh sách tin nhắn liên hệ
- [ ] Hiển thị thông tin người gửi
- [ ] Hiển thị nội dung tin nhắn
- [ ] Có thể xóa tin nhắn spam

**Nhiệm vụ kỹ thuật**:
- Tạo trang danh sách liên hệ
- Hiển thị thông tin chi tiết
- Thêm chức năng xóa
- Test hiển thị tin nhắn

#### **US-CMS-022: Xóa Phản Hồi/Spam**
**Là một** admin/moderator  
**Tôi muốn** xóa phản hồi/spam không phù hợp  
**Để** duy trì chất lượng nội dung  

**Tiêu chí chấp nhận**:
- [ ] Chọn phản hồi để xóa
- [ ] Xác nhận trước khi xóa
- [ ] Xóa khỏi database
- [ ] Thông báo kết quả xóa

**Nhiệm vụ kỹ thuật**:
- Implement multi-select
- Tạo modal xác nhận
- Xóa từ database
- Thêm thông báo kết quả

#### **US-CMS-023: Cấu Hình Cài Đặt Chung**
**Là một** admin  
**Tôi muốn** cấu hình cài đặt chung của website  
**Để** tùy chỉnh hệ thống theo nhu cầu  

**Tiêu chí chấp nhận**:
- [ ] Form cài đặt các thông số chung
- [ ] Lưu cài đặt vào database
- [ ] Áp dụng cài đặt ngay lập tức
- [ ] Có thể reset về mặc định

**Nhiệm vụ kỹ thuật**:
- Tạo trang cài đặt
- Implement lưu cài đặt
- Clear cache khi thay đổi
- Thêm nút reset

#### **US-CMS-024: Bật/Tắt Chế Độ Duyệt**
**Là một** admin  
**Tôi muốn** bật/tắt chế độ duyệt nội dung  
**Để** kiểm soát quy trình xuất bản  

**Tiêu chí chấp nhận**:
- [ ] Toggle bật/tắt duyệt bài viết
- [ ] Toggle bật/tắt duyệt di tích
- [ ] Cài đặt được lưu vào database
- [ ] Thay đổi có hiệu lực ngay

**Nhiệm vụ kỹ thuật**:
- Tạo toggle switches
- Implement logic duyệt
- Lưu vào SiteSetting
- Test chức năng toggle

#### **US-CMS-025: Cấu Hình Thông Tin Liên Hệ**
**Là một** admin  
**Tôi muốn** cấu hình thông tin liên hệ  
**Để** người dùng có thể liên hệ dễ dàng  

**Tiêu chí chấp nhận**:
- [ ] Form nhập thông tin liên hệ
- [ ] Lưu email, số điện thoại, địa chỉ
- [ ] Hiển thị trên trang liên hệ
- [ ] Có thể cập nhật bất kỳ lúc nào

**Nhiệm vụ kỹ thuật**:
- Tạo form thông tin liên hệ
- Lưu vào SiteSetting
- Hiển thị trên frontend
- Test cập nhật thông tin

#### **US-CMS-026: Xem Thống Kê Tổng Quan**
**Là một** admin  
**Tôi muốn** xem thống kê tổng quan hệ thống  
**Để** theo dõi hoạt động website  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị số lượng di tích, bài viết
- [ ] Hiển thị số lượng phản hồi, liên hệ
- [ ] Hiển thị thống kê theo thời gian
- [ ] Có biểu đồ trực quan

**Nhiệm vụ kỹ thuật**:
- Tạo trang dashboard
- Implement thống kê
- Tích hợp biểu đồ
- Test hiển thị dữ liệu

#### **US-CMS-027: Giao Diện Dễ Sử Dụng**
**Là một** admin/moderator  
**Tôi muốn** giao diện quản trị dễ sử dụng  
**Để** làm việc hiệu quả hơn  

**Tiêu chí chấp nhận**:
- [ ] Menu sidebar rõ ràng
- [ ] Form dễ điền và submit
- [ ] Thông báo rõ ràng
- [ ] Responsive trên mobile

**Nhiệm vụ kỹ thuật**:
- Cải thiện UI/UX
- Tối ưu form design
- Thêm thông báo toast
- Test responsive

#### **US-CMS-028: Loading Indicator**
**Là một** admin/moderator  
**Tôi muốn** thấy loading indicator khi tải trang  
**Để** biết hệ thống đang hoạt động  

**Tiêu chí chấp nhận**:
- [ ] Hiển thị loading khi tải trang
- [ ] Hiển thị loading khi submit form
- [ ] Hiển thị loading khi lọc dữ liệu
- [ ] Loading không bị gián đoạn

**Nhiệm vụ kỹ thuật**:
- Tạo loading component
- Implement loading states
- Thêm loading cho form
- Test loading indicator

#### **US-CMS-029: Thông Báo Phản Hồi Mới**
**Là một** admin/moderator  
**Tôi muốn** thấy thông báo khi có phản hồi mới  
**Để** phản hồi kịp thời  

**Tiêu chí chấp nhận**:
- [ ] Badge hiển thị số lượng phản hồi mới
- [ ] Badge xuất hiện ngay khi có phản hồi
- [ ] Click vào badge để xem danh sách
- [ ] Badge biến mất khi đã xem

**Nhiệm vụ kỹ thuật**:
- Tạo badge thông báo
- Implement real-time update
- Tạo link đến danh sách
- Test thông báo

#### **US-CMS-030: Lọc Tự Động**
**Là một** admin/moderator  
**Tôi muốn** lọc nội dung tự động khi chọn  
**Để** tiết kiệm thời gian thao tác  

**Tiêu chí chấp nhận**:
- [ ] Lọc tự động khi chọn dropdown
- [ ] Không cần nhấn nút tìm kiếm
- [ ] Kết quả cập nhật ngay lập tức
- [ ] Loading indicator khi lọc

**Nhiệm vụ kỹ thuật**:
- Thêm JavaScript auto-filter
- Implement real-time search
- Thêm loading khi lọc
- Test chức năng lọc

---

## **📊 Sprint Planning**

### **Sprint 1 (2 tuần) - Frontend Core**
- US-FE-001: Tải Trang Nhanh Chóng
- US-FE-002: Điều Hướng Mượt Mà
- US-FE-003: Thiết Kế Responsive
- US-FE-004: Chuyển Đổi Ngôn Ngữ

### **Sprint 2 (2 tuần) - Frontend Content**
- US-FE-005: Danh Sách Di Tích
- US-FE-006: Chi Tiết Di Tích
- US-FE-007: Bản Đồ Tương Tác
- US-FE-008: Thư Viện Hình Ảnh

### **Sprint 3 (2 tuần) - Frontend Interaction**
- US-FE-009: Đọc Blog
- US-FE-010: Tìm Kiếm Nội Dung
- US-FE-011: Form Liên Hệ
- US-FE-012: Gửi Phản Hồi

### **Sprint 4 (2 tuần) - Frontend UX**
- US-FE-013: Thông Báo Thành Công
- US-FE-014: Xem Phản Hồi
- US-FE-015: Tải Hình Ảnh Nhanh
- US-FE-016: Loading Indicators

### **Sprint 5 (2 tuần) - Frontend Performance**
- US-FE-017: Tự Động Cuộn Lên Đầu
- US-FE-018: Hiệu Suất Mượt Mà
- US-FE-019: Chế Độ Bảo Trì
- US-FE-020: Xử Lý Lỗi

### **Sprint 6 (2 tuần) - Frontend Final**
- US-FE-021: Graceful Degradation
- Testing và Bug fixes
- Performance optimization
- Cross-browser testing

### **Sprint 7 (2 tuần) - CMS Core**
- US-CMS-001: Quản Lý Danh Sách Người Dùng
- US-CMS-002: Phân Quyền Moderator
- US-CMS-003: Chặn Đăng Ký Người Dùng
- US-CMS-004: Xóa Người Dùng An Toàn

### **Sprint 8 (2 tuần) - CMS Content**
- US-CMS-005: Tạo Di Tích Mới
- US-CMS-006: Chỉnh Sửa Di Tích
- US-CMS-007: Duyệt Di Tích
- US-CMS-008: Tìm Kiếm Và Lọc Di Tích

### **Sprint 9 (2 tuần) - CMS Posts**
- US-CMS-009: Xem Chi Tiết Di Tích
- US-CMS-010: Tạo Bài Viết Mới
- US-CMS-011: Chỉnh Sửa Bài Viết
- US-CMS-012: Duyệt Bài Viết

### **Sprint 10 (2 tuần) - CMS Media**
- US-CMS-013: Tìm Kiếm Và Lọc Bài Viết
- US-CMS-014: Xem Chi Tiết Bài Viết
- US-CMS-015: Upload Hình Ảnh
- US-CMS-016: Xem Danh Sách Hình Ảnh

### **Sprint 11 (2 tuần) - CMS Management**
- US-CMS-017: Xóa Hình Ảnh
- US-CMS-018: Sắp Xếp Hình Ảnh
- US-CMS-019: Xem Phản Hồi Mới
- US-CMS-020: Đánh Dấu Phản Hồi Đã Xem

### **Sprint 12 (2 tuần) - CMS Final**
- US-CMS-021: Xem Tin Nhắn Liên Hệ
- US-CMS-022: Xóa Phản Hồi/Spam
- US-CMS-023: Cấu Hình Cài Đặt Chung
- US-CMS-024: Bật/Tắt Chế Độ Duyệt

### **Sprint 13 (2 tuần) - CMS Settings**
- US-CMS-025: Cấu Hình Thông Tin Liên Hệ
- US-CMS-026: Xem Thống Kê Tổng Quan
- US-CMS-027: Giao Diện Dễ Sử Dụng
- US-CMS-028: Loading Indicator

### **Sprint 14 (2 tuần) - CMS Notifications**
- US-CMS-029: Thông Báo Phản Hồi Mới
- US-CMS-030: Lọc Tự Động
- Testing và Bug fixes
- Performance optimization

---

## **🎯 Definition of Done**

### **Frontend User Stories**
- [ ] Component được tạo và test
- [ ] Responsive design hoạt động trên tất cả thiết bị
- [ ] API integration hoàn tất
- [ ] Error handling được implement
- [ ] Loading states được thêm
- [ ] Cross-browser compatibility test

### **CMS User Stories**
- [ ] Controller và Model được tạo
- [ ] Blade template được tạo
- [ ] Form validation hoạt động
- [ ] Permission check được implement
- [ ] AJAX functionality hoạt động
- [ ] Error handling được thêm

### **General Requirements**
- [ ] Code được review
- [ ] Unit tests được viết
- [ ] Documentation được cập nhật
- [ ] Performance được tối ưu
- [ ] Security được kiểm tra
- [ ] User acceptance testing hoàn tất

---

**📋 Epic và User Stories này cung cấp roadmap chi tiết cho việc phát triển dự án Global Heritage, đảm bảo tất cả tính năng được implement một cách có hệ thống và đáp ứng đúng nhu cầu người dùng.**


