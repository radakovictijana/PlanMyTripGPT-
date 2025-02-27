import React, { useState, useEffect } from 'react';
import { Link, useParams } from 'react-router-dom';
import Footer from './Footer';
import Header from './Header';
import axios from 'axios';

const Details = () => {
  const { id } = useParams();
  const IDnum = parseInt(id, 10);
  const [travel, setTravel] = useState([]);
  const [image, setImage] = useState(null);
  const [imageUrl, setImageUrl] = useState(null);
  const [success, setSuccess] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchTravels = async () => {
      const token = localStorage.getItem("auth_token");
      try {
        const response = await axios.get(`api/travels/${IDnum}/activity`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        console.log(id);
        console.log(IDnum);
        setTravel(response.data.data);
      } catch (err) {
        console.error('Error fetching users:', err.response || err.message);
      }
    };

    fetchTravels();
  }, [IDnum]);

  const handleFileChange = (event) => {
    setImage(event.target.files[0]);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('image', image);

    try {
      const token = localStorage.getItem('auth_token');
      const response = await axios.post('api/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'Authorization': `Bearer ${token}`, // Ako koristiš autentifikaciju
        },
      });
      console.log("File path:", response.data.file_path);
      setImageUrl(response.data.file_path); // Postavi URL slike
      setSuccess('Image uploaded successfully');
      setError('');
    } catch (err) {
      setError('Image upload failed');
      setSuccess('');
    }
  };

  return (
    <>
      <Header />
      <div className='container'>
        <div className='back-text'>
          <Link to="/travels" id="back-link" style={{ color: 'white', textDecoration: 'none', backgroundColor: 'grey', padding: '0.5rem 1rem' }}>
            Back
          </Link>
        </div>
        <div className="row">
          <div className="col-md-6">
            <div className='title'>
              <h1>{travel.destination}</h1>
            </div>
            <div className='group' id="acordian" role='tablist'>
              <div className='middle'>
                <h6>Start date: {travel.start_date}</h6>
                <h6>End date: {travel.end_date}</h6>
                <div className='aktivnosti' role='tab' id='headingOne'>
                  <h5>Daily plans:</h5>
                  {travel.daily_plans && travel.daily_plans.map((plan) => (
                    <div key={plan.id}>
                      <p>{plan.day}. day</p>
                      <p>Description: {plan.description}</p>
                      <p>Activity: {plan.activity}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
          <div className="col-md-4">
            <div className='image-section'style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '20px' }}>
              <h1>Upload Image</h1>
              <form onSubmit={handleSubmit}>
                <label htmlFor="image">Choose an image to upload:</label>
                <input
                  type="file"
                  id="image"
                  name="image"
                  accept="image/*"
                  onChange={handleFileChange}
                />
                <button type="submit">Upload Image</button>
              </form>
              <div>
              {imageUrl && <img src={"http://127.0.0.1:8000"+imageUrl} alt="Uploaded" style={{ marginTop: '20px', maxWidth: '100%', height: 'auto' }} />}
              {success && <p>{success}</p>}
              {error && <p>{error}</p>}
              </div>
             
              
            </div>
          </div>
          
        </div>
      </div>
      <Footer />
    </>
  );
};

export default Details;
