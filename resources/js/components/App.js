import React, { Component, Suspense } from 'react'
import ReactDOM from 'react-dom';

class App extends Component {
    render() {
        return <h1>react here</h1>
    }
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
