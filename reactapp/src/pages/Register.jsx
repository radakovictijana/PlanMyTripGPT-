import React from 'react'
import { useState} from 'react';
import { Link,useNavigate } from 'react-router-dom';
import '../App.css';
import TextField from '../components/shared/TextField';
import axios from 'axios';

function Register() {

  const [success,setSuccess] = useState(null);
  const [error, setError] = useState("");
  let navigate = useNavigate();
  const [userData,setUserData]= useState({
    name: "",
    email:"",
    password:"",
    
});

  function handleChange(e){
    setUserData({
      ...userData,
      [e.target.name]: e.target.value,
    });
  }

  async function handleSubmit(e){
    e.preventDefault();
    try{
      console.log('user data ', userData);
      const response = await axios.post("api/register", userData);

      const token = response.data.access_token;
      localStorage.setItem('auth_token', token);
      console.log('token ', token);
      console.log('data ', response.data);
      setSuccess('Successful registration!');
      setError(null);
      navigate('/login');

    }catch(e){
        setSuccess(null);
        setError('Register failed')
    } 
  }

    return (
        <div className='login-page'>
                 

        <div className='login-header'>
            <h1>Create a new account!</h1>
        </div>
      <div className="login-container">
        <form className='login-form' onSubmit={handleSubmit}>
            <div className='form-group'>
            <label htmlFor='name'>Name</label>
            <TextField
            type="text"
            name="name"
            id="name"
            value={userData.name}
            onChange={handleChange}
            required= {true}
            />
            
        </div>
          <div className='form-group'>
            <label htmlFor='email'>Email</label>
            <TextField
                      type="text"
                      name="email"
                      id="email"
                      value ={userData.email}
                      onChange={handleChange}
                      required={true}
                  />
          </div>
          <div className="form-group">
            <label htmlFor='password'>Password</label>
            <TextField
                      type="password"
                      name='password'
                      id='password'
                      value={userData.passeord}
                      onChange={handleChange}
                      required={true}
                />
          </div>
          <button type='submit' className='login-button' style={{width:'100px', height:'40px'}}>Register</button>
        </form>
        {error && <p style={{ color: 'red' }}>{error}</p>}
        <div className='register-link'>Have an account?<Link to='/login'>Log in here</Link> </div>
       </div>
       </div>
    )
}

export default Register