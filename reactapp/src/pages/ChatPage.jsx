import React from 'react'
import {Link} from 'react-router-dom'
import TextField from '../components/shared/TextField';
import { useState } from 'react'
import axios from 'axios';
import DatePicker from 'react-datepicker'
function ChatPage() {
  const [updatedTravels, setUpdatedTravels]= useState([]);
  const [updatedTravel, setUpdatedTravel]= useState();
  const[travels, setTravels]= useState([]);
  const [travel, setTravel] = useState({
    destination:"",
    start_date:"",
    end_date:"",
    guide:""
  });

 async function handleUpdate(e,p){
  e.preventDefault();
  //console.log(p);
  const token = localStorage.getItem('auth_token');
  const response = await axios.put('api/regenerateDay', p, {
    headers: {
      'Authorization': `Bearer ${token}`,
    }
  });

  if (response.data && response.data.plan) {
    setUpdatedTravel(response.data.plan);
  } else {
    console.error('Plan is not available in the response');
  }
  
  const updatedDailyPlan = response.data.plan;
  console.log('updatedDailyPlan ', updatedDailyPlan);
  // Ažurirajte state sa novim podacima
  setTravels(travels.map((travel) => 
    travel.id === updatedDailyPlan.id 
  ? { ...travel, description: updatedDailyPlan.description, activity: updatedDailyPlan.activity }
  : travel
  ));
  
  // setTravels(updatedTravels);
  
  
 }
  async function handleSubmit(e){
  
    e.preventDefault();
    const token = localStorage.getItem('auth_token');
    
    const response = await axios.post('api/gpt',travel, {
      headers: {
        'Authorization': `Bearer ${token}`,
      }
    });
    console.log(response);
    setTravels(response.data.plans);
    console.log(travels)


  }
  function handleChange(e){
    setTravel({
      ...travel,
      [e.target.name]:e.target.value,
    });
  }
  return (
    <div className='container'>
        
        <div className='row'>
          <div className='col-md-4'>
            <form className='travel-form' onSubmit={handleSubmit}>
            <div className='form-group'>
            <label>Destination</label>
              <TextField
                type ="text"
                name ="destination"
                id="destination"
                value = {travel.destination}
                onChange={handleChange}
                required= {true}

              />
            </div>
            <div className='form-group'>
            <label>Start date</label>

              <TextField
                type ="date"
                name ="start_date"
                id="start_date"
                value = {travel.start_date}
                onChange={handleChange}
                required= {true}

              />
            </div>
            <div className='form-group'>
            <label>End date</label>
              <TextField
                type ="date"
                name ="end_date"
                id="end_date"
                value = {travel.end_date}
                onChange={handleChange}
                required= {true}

              />
            </div>
            <div className='form-group'>
              <label>Do you need guide?Type yes or no!</label>
              <TextField
                type ="text"
                name ="guide"
                id="guide"
                value = {travel.guide}
                onChange={handleChange}
                required= {true}

              />
            </div>
            <button type='submit'style={{width:'200px', height:'40px'}}>Generate travel plan</button>
            </form>
           
          </div>
          <div className='col-md-8'>
            <h1 style={{textAlign:'center'}}>Generated travel plan</h1>
            <table className='table'>
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Description</th>
                  <th>Activity</th>
                  <th></th>
                  
                </tr>
                
              </thead>
              <tbody>
                  {travels!==null ?travels.map((p,index)=>
                   <tr key={index}>
                   <td>{p.day}</td>
                   <td>{p.description}</td>
                   <td>{p.activity}</td>
                   <td><button type='button' onClick={(e) => handleUpdate(e,p)}>Update</button></td>
                 </tr>): (<tr>Loading</tr>) }
              </tbody>
            </table>
          </div>
        </div>
        
    </div>
  )
}

export default ChatPage