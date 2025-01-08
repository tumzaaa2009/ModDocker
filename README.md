# ModDocker
คำสั่งเปิดพอร์ต
firewall-cmd --add-port=9000/tcp

firewall-cmd --add-port=9000/tcp --permanent
firewall-cmd --reload



ตั้งค่า nginx.conf -> conf 
เปิดแค่ พอร์ต 443 
นำไฟล์ ssl ไปวางใน folder ตามไฟล์ตัวอย่างได้เลย
ต้อง  cat willcard_mop.crt DigiCertCA.crt >> bundle.crt
