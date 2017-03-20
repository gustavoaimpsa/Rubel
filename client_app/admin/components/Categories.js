import React, {Component, PropTypes} from 'react';
import {reduxForm, Field, SubmissionError} from 'redux-form';
import {connect} from 'react-redux';
import {createCategory, fetchCategories, deleteCategory} from '../actions/index';
import {Link} from 'react-router';

const validate = props => {
  const errors = {}

  if (!props.name) {
    errors.name = 'Required'
  }

  return errors;
}

const renderInputField = ({
  input,
  label,
  type,
  meta: {
    touched,
    error
  }
}) => (
  <div>
    <label>{label}</label>
    <div>
      <input {...input} placeholder={label} type={type}/> {touched && ((error && <span>{error}</span>))}
    </div>
  </div>
)

class Categories extends Component {
  onSubmit(props) {
    const {createCategory, fetchCategories, reset} = this.props;

    return createCategory(props).then((res) => {
      if (res.error) {
        const validation_msg = res.payload.response.data.messages

        throw new SubmissionError({
          name: [validation_msg.name]
        });
      } else {
        reset();
        fetchCategories();
      }
    });
  }

  handleClick(props) {
    const {fetchCategories} = this.props;

    return deleteCategory(props).then((res) => {
      if (res.error) {
        console.log('Error!');
      } else {
        fetchCategories();
      }
    });
  }

  componentWillMount() {
    this.props.fetchCategories();
  }

  renderCategories() {
    return this.props.categories.map((category) => {
      return (
        <li key={category.id}>
          {category.name}
          <button onClick={this.handleClick.bind(this, category.id)}>✕</button>
        </li>
      );
    });
  }

  render() {
    const {handleSubmit} = this.props

    return (
      <div>
        <form onSubmit={handleSubmit(this.onSubmit.bind(this))}>
          <Field name="name" type="text" component={renderInputField} placeholder="Category Name"/>
          <button type="submit">Submit</button>
        </form>
        {this.renderCategories()}
      </div>
    );
  }
}

Categories.contextTypes = {
  router: PropTypes.object
}

const form = reduxForm({form: 'CategoryForm', validate})(Categories)

function mapStateToProps(state) {
  return {categories: state.categories.all};
}

export default connect(mapStateToProps, {createCategory, fetchCategories})(form);
