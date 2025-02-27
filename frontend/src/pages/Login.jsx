import React from 'react'
import { useState,useEffect } from 'react'
import '../App.css';
import { Link ,useNavigate} from 'react-router-dom';
import TextField from '../components/shared/TextField';
import NavBar from '../components/NavBar';
import axios from 'axios';

function Login({addToken}) {
  const navigate = useNavigate();
  const[userData,setUser] = useState({
    email:"",
    password:"",
  });
  const [error,setError] = useState(null);
  const [success,setSuccess] = useState(null);

  function handleChange(e){
    setUser({
      ...userData,
      [e.target.name]: e.target.value,
    });

  }
  
 
  async function handleSubmit(e){
    e.preventDefault();
    try{
      console.log('user data ', userData);
      const response = await axios.post("api/login", userData);

      const token = response.data.access_token;
      const user = response.data.user;
      const userId = user.id;
      const userRole = user.role;
      addToken(token);

      localStorage.setItem('auth_token', token);
      localStorage.setItem('user', user);
      localStorage.setItem('user_id', userId);
      localStorage.setItem('user_role', userRole);

      console.log('token ', token);
      console.log('data ', response.data);
      setSuccess('Successful login');
      setError(null);
      navigate('/chatGpt');

    }catch(e){
        setSuccess(null);
        setError('Login failed')
    }
  }
  
  return (
    <div className='login-page'>

      <div className='login-header'>
            <h1>Log In</h1>
        </div>
    <div className="login-container">
      <form className='login-form' onSubmit={handleSubmit}>
      
        <div className='form-group'>
          <label htmlFor='email'>Email</label>
          <TextField
                    type="text"
                    name="email"
                    id="email"
                    value ={userData.email}
                    onChange={handleChange}
                    required= {true}
                />
        </div>
        <div className="form-group">
          <label htmlFor='password'>Password</label>
          <TextField
                    type="password"
                    name='password'
                    id='password'
                    value={userData.password}
                    onChange={handleChange}
                    required={true}
              />
        </div>
        
          <button type='submit' className='login-button' style={{width:'100px', height:'40px'}}>Login</button>
          
       
      </form>
      <div className='register-link'>Don't have an account?<Link to='/register'>Register here!</Link> </div>
     </div>
     
    </div>
  )
}

export default Login