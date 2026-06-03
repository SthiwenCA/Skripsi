from flask import Flask, request, jsonify
from ultralytics import YOLO
import os
import uuid
import tempfile # Tambahan: Untuk menggunakan folder /tmp bawaan sistem operasi
from collections import Counter

app = Flask(__name__)

# 1. Menggunakan path absolut agar aman saat dijalankan sebagai background service di VPS
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, 'best.pt')

try:
    model = YOLO(MODEL_PATH)
    print("Model AI berhasil dimuat!")
except Exception as e:
    print(f"Error memuat model: {e}")

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'Tidak ada file gambar'}), 400

    file = request.files['image']
    
    # 2. Menggunakan folder temporary bawaan Linux (/tmp) agar tidak nyampah dan aman
    temp_dir = tempfile.gettempdir()
    temp_filename = os.path.join(temp_dir, str(uuid.uuid4()) + ".jpg")
    
    file.save(temp_filename)

    try:
        results = model(temp_filename)
        detected_damages = []
        
        for result in results:
            boxes = result.boxes
            for box in boxes:
                class_id = int(box.cls[0])
                class_name = model.names[class_id]
                detected_damages.append(class_name)

        if os.path.exists(temp_filename):
            os.remove(temp_filename)

        if len(detected_damages) > 0:
            damage_counts = Counter(detected_damages)
            most_common_damage = damage_counts.most_common(1)[0][0]
            final_damage = most_common_damage.lower() 
        else:
            final_damage = 'aman'

        return jsonify({
            'damage_type': final_damage,
            # Ubah Counter object ke dictionary biasa agar bisa di-convert jadi JSON
            'all_detections': dict(damage_counts) if len(detected_damages) > 0 else {}, 
            'total_boxes': len(detected_damages)
        })

    except Exception as e:
        if os.path.exists(temp_filename):
            os.remove(temp_filename)
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    # 3. Ubah debug=True menjadi False untuk keamanan Production di VPS
    app.run(host='0.0.0.0', port=5000, debug=False)