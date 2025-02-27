import React from "react";

const TextField =({type,name,id,value,onChange,required=false,placeholder=""}) =>(  
    <input
    type ={type}
    name={name}
    id ={id}
    className='form-control'
    value={value}
    onChange={onChange}
    required={required}
    placeholder={placeholder}
    />
);
export default TextField;
