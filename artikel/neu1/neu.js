const editor = new EditorJS({

  placeholder: 'Schreibe los...',

  tools: {
    header: {
      class: Header,
      config: {
        placeholder: 'Überschrift hinzufügen...'
      }
    },
    image: SimpleImage,
    list: List
  },
})
