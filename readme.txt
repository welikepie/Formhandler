Formhandler is a way for people more unfamiliar with HTML to produce HTML forms
using a little PHP and a particular syntax.

 <%formstart>
    A demarkation tag to start the form, handles on submit values and all the rest of it.

<%{text = "RadioStation"}{inputtype = "text"}{ name="RadioStation"}{ value="anything"}>
    <% demarkation and then values. Values are written as the usual form field way, encapsulated with {} and closed with >
    Any standard HTML declarations for inputtype will work.
    Text; text for field.
    Inputtype; input type (radio, check, etc.)
    Name; What the button's called to the backend.
    Value; what the button defaults to (or what textboxes are filled with).
<%formfinish>
    Demarkation to finish the form creation process and to add a "submit" button.

Have fun, and any further questions, feel free to email at alex@welikepie.com