import React from 'react';
import Message from './message';

export default class Form extends React.Component {
    constructor(props){
        super(props);
        this.state = {
			data: {
            name: 'a',
            email: '',
            tel: '',
            comment: '',
			}
        }
    }
     
    render() {
        return (
            <form method="post" className="form">
			
                <Message data = {this.state.data}/>
			
                <input type="submit" value="送信する" className="submit" />
            </form>
        );
    }
}

