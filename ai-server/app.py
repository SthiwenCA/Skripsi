from flask import Flask, request, jsonify
from ultralytics import YOLO
import os
import uuid

app = Flask(__name__)

# 1. Load Model YOLOv8 (File best.pt kamu)
# Pastikan file best.pt berada di folder yang sama dengan app.py ini
try:
    model = YOLO('best.pt')
    print("Model AI berhasil dimuat!")
except Exception as e:
    print(f"Error memuat model: {e}")

@app.route('/predict', methods=['POST'])
def predict():
    # Cek apakah ada file gambar yang dikirim dari Laravel
    if 'image' not in request.files:
        return jsonify({'error': 'Tidak ada file gambar'}), 400

    file = request.files['image']
    
    # Buat nama file unik sementara agar tidak bentrok jika ada banyak laporan bersamaan
    temp_filename = str(uuid.uuid4()) + ".jpg"
    file.save(temp_filename)

    try:
        # 2. Proses Deteksi Gambar dengan YOLOv8
        results = model(temp_filename)
        
        detected_damages = []
        
        # Ekstrak hasil deteksi (Bounding boxes)
        for result in results:
            boxes = result.boxes
            for box in boxes:
                class_id = int(box.cls[0])
                class_name = model.names[class_id] # Mengambil nama kelas (misal: crack, pothole)
                detected_damages.append(class_name)

        # 3. Hapus gambar sementara dari server AI agar memori tidak penuh
        if os.path.exists(temp_filename):
            os.remove(temp_filename)

        # 4. Tentukan kerusakan dominan (Ambil yang pertama terdeteksi)
        if len(detected_damages) > 0:
            # Ubah ke huruf kecil agar seragam dengan Leaflet di Laravel
            final_damage = detected_damages[0].lower() 
        else:
            final_damage = 'aman' # Jika tidak ada jalan rusak yang terdeteksi

        # 5. Kirim balasan ke Laravel
        return jsonify({
            'damage_type': final_damage,
            'all_detections': detected_damages # Mengirim semua deteksi (opsional untuk log)
        })

    except Exception as e:
        # Bersihkan file jika terjadi error saat memproses
        if os.path.exists(temp_filename):
            os.remove(temp_filename)
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    # Server AI akan menyala di Port 5000
    app.run(host='0.0.0.0', port=5000, debug=True)