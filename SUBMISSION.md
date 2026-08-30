# SUBMISSION - Exit Exam MVC 1/2569 (อาทิตย์เช้า)

## 1. วิธีเปิดโปรแกรม
- **ภาษา/เฟรมเวิร์ก:** PHP 8.2 ล้วน หน้าเว็บใช้ Bootstrap 5.3 ผ่าน CDN
- **Entry point / คำสั่งเปิดโปรแกรม:**
  - แบบ XAMPP: วางโฟลเดอร์ไว้ใน `C:\xampp\htdocs\` แล้วเปิด `http://localhost/MVC-1-2569/index.php`
  - แบบไม่ต้องลง XAMPP: `php -S localhost:8000` ที่โฟลเดอร์โปรเจกต์ แล้วเปิด `http://localhost:8000/index.php`
- **หมายเหตุที่จำเป็น:**
  - ถ้าต้องการเริ่มข้อมูลใหม่ เปิด `index.php?reset=1`

## 2. ตารางเชื่อมโยง Requirements

| Requirement | Model / Domain | Controller / Action | View / Screen |
|---|---|---|---|
| R1 ดูรายชื่อผู้สมัคร | Candidate,Election.getCandidates() | VotingController.getCandidates()` | views/voting.php  |
| R2 ลงคะแนนแบบจัดอันดับ 3 อันดับ | Ballot, Voter, Election.vote() | VotingController.vote() action=vote | views/voting.php|
| R3 ปิดรับคะแนนและจับกลุ่มบัตรรูปแบบซ้ำ | PatternGroup, Election.close() | AdminController.close()  action=close |views/admin.php |
| R4 ตรวจกลุ่มบัตรซ้ำและสรุปผล | PatternGroup.decide(), Election::decideGroup(), Election.hasPendingGroup(), Election.finish() | `AdminController.decideGroup() action=decide,AdminController.finish()  action=finish | views/admin.php |
| R5 สรุปสถานะและผลการเลือกตั้ง | Election.getSummary(), Election.countScore() | `index.php เรียก Model โดยตรง | views/status.php |

## 3. ผลการทดสอบ

**วิธีทดสอบ:** รันโปรแกรมจริงแล้วไล่กดปุ่มบนหน้าเว็บตามลำดับการใช้งานจริง โดยอ่านฟอร์มจากหน้ากรอกข้อมูลออกมาแล้วส่งค่าตามที่ฟอร์มนั้นระบุ 
> ปรับหมายเลข T1–T6 ให้ตรงกับกรณีทดสอบในโจทย์ก่อนส่ง

| กรณี | ผ่าน/ไม่ผ่าน | หมายเหตุ (เฉพาะที่จำเป็น) |
|---|---|---|
| T1 เปิดหน้า "ลงคะแนน" ดูรายชื่อผู้สมัคร | ผ่าน |  |
| T2 เลือก V04 กับอันดับ C01>C02>C03 แล้วกด | ผ่าน |  |
| T3 เลือก V04 คนเดิมแล้วกดอีกครั้ง | ผ่าน |  |
| T4 เลือก C01 ซ้ำสองอันดับแล้วกด | ผ่าน | 
| T5 กดในหน้าเจ้าหน้าที่ | ผ่าน | |
| T6 กดขณะกลุ่มยังรอตรวจสอบ | ผ่าน |  |

## 4. ความแตกต่างระหว่างแบบที่ออกกับโปรแกรมจริง

1. **เพิ่ม ?reset=1 และตัวนับ $dataVersion ใน config.php** สำหรับล้างข้อมูลใน session กลับไปตั้งต้น ใช้ตอนทดสอบซ้ำหลายรอบ

## 5. บันทึกการใช้ Generative AI
| เวลาโดยประมาณ | เครื่องมือ | ใช้เพื่ออะไร | นำคำแนะนำไปใช้อย่างไร |
|---|---|---|---|
| 10:00| claude |ถามคาวมถูกต้องเกี่ยวกับ class diagram ว่าควรเพิ่มหรือลดจุดไหนมั้ย|แนะนำมาว่าให้เพิ่ม decideGroup เพื่อทำการแล้ววนอัปเดต status ของบัตรทุกใบในกลุ่มนั้นให้ตรงกับผลตัดสิน|
| 10:00 |claude| สอบถามการนำ .json มาใช้ใน php | แนะนำให้ใช้ file_get_contents() อ่านไฟล์เป็น string แล้ว json_decode() แปลง string นั้นเป็น array/object ของ PHP |
| 10:15 |claude| สอบถาม systax ในการใช้ การดึงข้อมูลออกมาใช้  | เอา Systax ที่แนะนำมาปรับใช้ในโค้ด  |
| 10:50 |claude |โปรแกรมพังหลังเปลี่ยนชื่อ ถามว่าเกิดจากอะไร | ได้เข้าใจว่า PHP เก็บ object ลง session แบบ serialize ตัวที่ค้างอยู่จึงยังเป็น property ชื่อเก่า พอโค้ดใหม่เรียกชื่อใหม่จึงได้ null จากนั้นใส่ตัวนับ $dataVersion ใน config.php ให้ล้าง session อัตโนมัติเมื่อโครงข้อมูลเปลี่ยน |
| 11:20 | claude |ไฟล์ใน views  eoror ใน VS Code ถามว่าคืออะไร | Intelephense ฟ้อง undefined variable เพราะ view รับตัวแปรมาทาง include ตัวตรวจอ่านทีละไฟล์เลยไม่รู้ที่มา แก้ด้วยการประกาศ docblock @var ไว้หัวไฟล์ |
| 11:50 | claude | สอบถามการเทส แต่ละเคส เนื่องจาก req มีการบอกว่าถ้าปิดรับแล้วไม่สามารถลงคะแนนได้ |แนะนำเทสผ่าน Http กับสร้างปุ่มใหม่เพื่อเทสโดยเฉพาะ เลยทำการเลือกปุ่ม เพื่อที่จะเห็นข้อมูลที่เรากรอกไปจริงๆ โดยทำการ set  index.php?reset=1 เพื่อเริ่มกรอกใหม่|
 

